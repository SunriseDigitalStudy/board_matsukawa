<?php
/**
 * get/setで始まるメソッドはカラム名とぶつかるので作りませんでした。
 * set～、get～というメソッドは使用しないでください
 * getter/setterが少しおかしな名前になってるのはそのせいです
 * swap・bind・fromArrayなど
 * 
 * @package Sdx_Db
 *
 * @author  Masamoto Miyata <miyata@able.ocn.ne.jp>
 * @create  2009/09/03
 * @copyright 2007 Sunrise Digital Corporation.
 * @version  v 1.0 2009/09/03 18:25:50 Miyata
 **/

abstract class Sdx_Db_Record implements Sdx_Db_Adapter_SavePoint_BeginTransaction
{
	const DATE_COMPARE_LATER = 1;
	const DATE_COMPARE_EARLIER = -1;
	const DATE_COMPARE_EQUAL = 0;
	
	/**
	 * 
	 * レコードを返すメソッドには影響しません。
	 * $record->get('column_name');
	 * @var unknown_type
	 */
	private static $_return_sdx_null_non_exists_column = false;
	
	private $_change_history = array();
	
	public function getImgTag($column, $alt = "", $width = null, $extentd = null)
	{
		$path = $this->get($column);
		
		if($this->_isEmptyValue($path))
		{
			return '';
		}
		else
		{
			$tag = '<img src="'.$path.'" alt="'.$alt.'" ';
		
			if($width)
			{
				$tag .= 'width="'.$width.'" ';
			}
			
			if($extentd)
			{
				$tag .= $extentd.' ';
			}
			
			return $tag .= '/>';
		}
	}
	
	/**
	 * 
	 * カラム名の配列を返す
	 * ここで返したカラムは変更履歴を配列で取れる
	 */
	protected function _needHistoryColumns()
	{
		return array();
	}
	
	public function getChangeHistory($column)
	{
		if(!in_array($column, $this->_needHistoryColumns()))
		{
			throw new Sdx_Exception("Target column is not in _needHistoryColumns()");
		}
		
		return isset($this->_change_history[$column])
			? $this->_change_history[$column]
			: array();
	}
	
	public static function enableReturnSdxNullForNonExistsColumn()
	{
		self::$_return_sdx_null_non_exists_column = true;
	}

	/**
	 * @var array データベースから読み込んだデータ
	 */
	protected $_values  = array();

	/**
	 * @var array 更新されたデータ
	 */
	protected $_updates = array();

	/**
	 * @var boolean 新規レコードかどうか
	 */
	protected $_is_new = true;

	protected $_is_deleted = false;

	/**
	 * リレーションオブジェクトの配列
	 * @var array Sdx_Db_Record
	 */
	protected $_relations = array();

	/**
	 * getされる前にaddされたリレーションオブジェクト（1対多　多対多）
	 */
	protected $_tmp_relations = array();

	/**
	 * 削除するリレーションオブジェクト
	 * many_many
	 */
	protected $_delete_relations = array();

	/**
	 * booleanの配列
	 * setでセットされたリレーションで、ほかレコードを持っていたら
	 * 例外を投げるタイプ
	 * one_many ref_one
	 */
	protected $_exception_relations = array();

	/**
	 * オブジェクトの配列
	 * setでセットされたリレーションで、ほかレコードを削除するもの
	 * 多対多
	 */
	protected $_swap_relations = array();

	/**
	 * saveをTransacctionが呼ばれた時点まで遅らせるかどうかのフラグ
	 */
	protected $_delay_save = false;

	/**
	 * クラス継承を使った方がいいのでやめます
	 * @var Sdx_Notification_Center
	 */
	//protected static $_nc;

	/**
	 * リレーションを保存したかどうかのフラグ
	 *
	 * @var boolean
	 */
	protected $_relation_save;


	/**
	 * syncが必要かのフラグ
	 * save時にリセットします
	 */
	protected $_need_sync = true;


	private $_unserialize_value = array();

	public function __construct()
	{
		$this->_relation_save = true;
	}

	public function getTruncatedColumn($column, $length, $etc = '...', $break_words = false, $middle = false)
	{
		$string = $this->get($column);
		if (is_string($string) && mb_strlen($string) > $length)
		{
			$length -= min($length, mb_strlen($etc));
			if (!$break_words && !$middle)
			{
			    $string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1));
			}

			if (!$middle)
			{
			    return mb_substr($string, 0, $length) . $etc;
			}
			else
			{
			    return mb_substr($string, 0, $length / 2) . $etc . mb_substr($string, - $length / 2);
			}
		}

		return $string;
	}

	/**
	 * @return Zend_Date
	 * @param unknown_type $column
	 */
	public function getZendDate($column)
	{
		$value = $this->_get($column);

		$date = null;
		if($value)
		{
			$db = $this->referenceConnection();
			$date = $db->createZendDate($value);
		}

		return $date;
	}

	public function isColumnUpdated($column)
	{
		return isset($this->_updates[$column]);
	}

	/**
	 *
	 * Zend_Dateを使って日付カラムを整形する
	 * フォーマット例
	 * yyyy-MM-dd HH:mm:ss MySQL
	 * 'yyyy M/d(EE)'　2010 7/1(木)
	 * 'yyyy MM/dd HH:mm:ss' 2010 07/01 19:54:29
	 * 'yyyy MM/dd(EE) a H:m:ss' 2010 07/01(木) 午後 7:54:29
	 *
	 * 詳しくは↓
	 * http://framework.zend.com/manual/ja/zend.date.constants.html#zend.date.constants.selfdefinedformats
	 *
	 * 25:00/26:00など実際には存在しない時間を通すと挙動がおかしくなります。
	 *
	 * @param string $column
	 * @param string $format
	 * @return string
	 */
	public function getFormatedDateByZend($column, $format)
	{
		$date = $this->getZendDate($column);

		$value = '';
		if($date)
		{
			$value = $date->toString($format);
		}

		return $value;
	}

	public function updateToCurrent($column)
	{
		$db = $this->referenceConnection();
		$this->set($column, $db->getCurrentTimestamp());
		return $this;
	}

	/**
	 * 主キーを比べて同じレコードを参照したオブジェクトか調べる
	 * $strict = trueの時は全カラムを調べます
	 * TODO主キーがnull（新規レコード）の時はnullを返すはどうなのか？
	 * TODOレコードが同じオブジェクトの時はtrueを返すのはどうなのか？
	 */
	public function isEquals(Sdx_Db_Record $record, $strict = false)
	{
		if($this === $record)
		{
			return true;
		}

		$pkeys = $record->_getTable()->getPkeys();

		if($strict)
		{
			$columns = $record->_getTable()->getColumns();
		}
		else
		{
			$columns = $pkeys;
		}

		foreach($columns as $column)
		{
			$this_value = $record->_get($column);
			$record_value = $this->_get($column);

			if(in_array($column, $pkeys))
			{
				if($this_value === null)
				{
					return false;
				}

				if($record_value === null)
				{
					return false;
				}
			}

			if($this_value != $record_value)
			{
				return false;
			}
		}

		return true;
	}

	public function toArray()
	{
		return array_merge($this->_values, $this->_updates);
	}

	/**
	 * 変更されたことがあるデータについて、変更前と変更後の両方を返す
	 * @return array(変更前データ, 変更後データ)
	 */
	public function toArrayModifiedDataOnly()
	{
		$s = array();
		$d = array();
		foreach ($this->_updates as $key => $value)
		{
			$d[$key] = $value;
			$s[$key] = isset($this->_values[$key]) ? $this->_values[$key] : null;
		}
		return array($s, $d);
	}

	/**
	 * 配列のデータを自分にsetする
	 * TODO: 引数を参照にできないか？
	 * @param array $array
	 * @return Sdx_Db_Record
	 */
	public function fromArray(array $array)
	{
		foreach($array as $key=>&$value)
		{
			$this->set($key, $value);
		}

		return $this;
	}

	/**
	 * var_dumpのrucursive？と同じ問題が発生とりあえず、自分のみ表示するように変更
	 */
	/*private function _toArray()
	{
		if(!$this->_already_to_array)
		{
			$this->_already_to_array = true;
			$array = array_merge($this->_values, $this->_updates);
			foreach($this->_relations as $key=>$relation)
			{
				if($relation instanceof Sdx_Db_Record_List)
				{
					//TODO　Sdx_Db_Record_List::toArrayでいいんじゃね？
					$records = array();
					foreach($relation as $rel)
					{
						$records[] = $rel->_toArray($this->getTable()->getClassSuffix());
					}

					$array[$key] = $records;
				}
				else
				{
					if(!$relation)
					{
						$array[$key] = null;
					}
					else
					{
						$array[$key] = $relation->_toArray($this->getTable()->getClassSuffix());
					}
				}

			}
			return $array;
		}
	}*/

	/**
	 * @return Zend_Db_Adapter_Abstract
	 */
	public function referenceConnection()
	{
		return $this->_getTable()->referenceConnection();
	}

	/**
	 * @return Sdx_Db_Adapter
	 */
	public function updateConnection()
	{
		return $this->_getTable()->updateConnection();
	}

	public function pkeyValue()
	{
		$pkey =  $this->_getTable()->getPkey();
		return isset($pkey) ? $this->_get($pkey) : null;
	}

	public function pkeyValues()
	{
		$pkeys = $this->_getTable()->getPkeys();

		$values = array();
		foreach($pkeys as $pkey)
		{
			$values[$pkey] = $this->_get($pkey);
		}

		return $values;
	}

	/**
	 * DBから読み込んだDATAをセットするメソッド
	 * アップデイトには使わないでください
	 */
	public function bind(array $values)
	{
		$this->_values = $values;
		$this->_is_new = false;
		return $this;
	}

	/**
	 * 以下の順番で呼ばれます
	 * Sdx_Db_Record::set()>Sdx_Db_Record::setColumnName()>Sdx_Db_Record::_set()
	 * @return Sdx_Db_Record
	 */
	public function set($name, $value)
	{
		$method = 'set'.Sdx_Util_String::camelize($name);

		if(method_exists($this, $method))
		{
			return $this->$method($value);
		}
		else
		{
			$this->_values[$name] = $value;
		}

		return $this;
	}

	public function get($name, $default = null)
	{
		if($default === null && self::$_return_sdx_null_non_exists_column)
		{
			$default = Sdx_Null::getInstance();
		}

		//メソッドがあったら
		$method = 'get'.Sdx_Util_String::camelize($name);

		if(method_exists($this, $method))
		{
			$value = $this->$method();
			if($value === null)
			{
				return $default;
			}

			return $value;
		}

		//$nameに.が含まれなかったら（存在しないカラムがsetされてたらこれで取得）
		if(strpos($name, '.') === false)
		{
			return $this->_get($name, $default);
		}

		//含まれていたらメソッドをたどっていく
		$list = explode('.', $name);

		$tmp = $this;
		foreach($list as $key=>$na)
		{
			$method = 'get'.Sdx_Util_String::camelize($na);
			if(method_exists($tmp, $method))
			{
				$tmp = $tmp->$method();
			}
			else if(is_object($tmp))
			{
				$tmp = $tmp->_get($na, $default);
			}
			else
			{
				$tmp = $default;
			}
		}

		return $tmp;
	}
	
	/**
	 * get()によって取得した値が空かどうか調べるメソッド
	 * getの返り値は対象が空の場合、バージョン（設定）によってSdx_Nullかnullです
	 * 
	 * @param unknown_type $value
	 */
	private function _isEmptyValue($value)
	{
		return $value instanceof Sdx_Null || empty($value);
	}

	/**
	 * 存在するカラムの更新のみ担当。set～メソッドで内部的に呼んでます。
	 * @return Sdx_Db_Record
	 */
	protected function _set($name, $value)
	{
		if(in_array($name, $this->_needHistoryColumns()))
		{
			$old = $this->get($name);
			
			if(!$this->_isEmptyValue($old) && $value != $old)
			{
				$history = $this->getChangeHistory($name);
				$history[] = $old;
				$this->_change_history[$name] = $history;
			}
		}
		
		if (is_array($value))
		{
			//保存時に「Array」になってしまうので最初から入れられないようにする
			throw new Sdx_Db_Exception(
				'Cannot set array to column '.$name.' in '.$this->_getTable()->getTableName()
			);
		}

		if($this->_getTable()->hasColumn($name))
		{
			if($value === true)
			{
				$value = 1;
			}

			if($value === false)
			{
				$value = 0;
			}

			//DBから読み込んだデータは全てstringなのでstringで比べる
			$value = ($value === '' || $value === null) ? null : strval($value);

			if($this->_get($name) !== $value)
			{
				$this->_updates[$name] = $value;
			}
		}
		else
		{
			throw new Sdx_Db_Exception('Not exsits '.$name.' column in '.$this->_getTable()->getTableName());
		}

		return $this;
	}

	/**
	 * 内部変数のみから、getするメソッド。リレーションは調べません
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	protected function _get($name, $default = null)
	{
		if(in_array($name, array_keys($this->_updates)))
		{
			return $this->_updates[$name];
		}

		if(in_array($name, array_keys($this->_values)))
		{
			return $this->_values[$name];
		}

		return $default;
	}

	public function isNew()
	{
		return $this->_is_new;
	}
	
	protected function _isLoadedRelation($relname)
	{
		return array_key_exists($relname, $this->_relations);
	}

	/**
	 *
	 * @string $relname
	 * @return Sdx_Db_Record $this
	 */
	public function clearRecordCache($relname)
	{
		unset($this->_relations[$relname]);
		return $this;
	}

	public function clearAllRecordCash()
	{
		$this->_relations = array();

		return $this;
	}

	/**
	 * 更新が必要か自分のカラムのみを調べる
	 * リレーションは調べない
	 * 相互参照してる時のループ防止
	 */
	/*private function _needSave()
	{
		if(!empty($this->_updates))
		{
			return true;
		}

		//addされていた時は$this->_tmp_relationsを保存して空にしないと
		//getした時に（DBから読み込まれた+tmp）で一個レコードが重複してしまうので
		//tmpがあった時は保存する
		if(!empty($this->_tmp_relations))
		{
			return true;
		}

		return false;
	}*/

	private function _needSync()
	{
		return $this->_need_sync;
	}

	/**
	 * 再帰的にひたすらさかのぼって更新が必要か調べる
	 * あんまり必要ないかも
	 * TODO$recursiveはやめる
	 */
	public function isUpdated($recursive = false)
	{
		if(!empty($this->_updates))
		{
			return true;
		}

		if($recursive)
		{
			if(!empty($this->_old_relations))
			{
				return true;
			}

			//TODO _callbackAllRelationsにまとめられないか？
			foreach($this->_relations as $relations)
			{
				if(empty($relations))
				{
					continue;
				}

				if($relations instanceof Sdx_Db_Record)
				{
					$relations = array($relations);
				}

				foreach($relations as $relation)
				{
					if($relation->isUpdated($recursive))
					{
						return true;
					}
				}
			}
		}

		return false;
	}

	public function isDeleted()
	{
		return $this->_is_deleted;
	}

	protected function _preDelete(Sdx_Db_Adapter $db)
	{

	}

	protected function _postDelete(Sdx_Db_Adapter $db)
	{

	}

	public function delete(Sdx_Db_Adapter $db = null)
	{
		if($this->isNew())
		{
			return;
		}

		if(!$db)
		{
			$db = $this->updateConnection();
		}

		$table = $this->_getTable()->getTableName();
		$where = $this->_getPkWhere($db);

		$this->_preDelete($db);
		$row_count = $db->delete($table, $where);

		/*$this->_callbackAllRelations(
			array($this, '_checkReferenceRelationForDelete'),
			$this->_relations,
			$db
		);

		$this->_callbackAllRelations(
			array($this, '_checkReferenceRelationForDelete'),
			$this->_tmp_relations,
			$db
		);*/
		
		if($row_count > 0)
		{
			$this->_postDelete($db);
		}
		

		$this->_is_new = false;
		$this->_is_deleted = true;
		$this->_updates = array();
	}

	/**
	 * トランザクション突入前に呼ばれるとトランザクション突入直後にdeleteされる
	 * @param Sdx_Db_Adapter $db
	 */
	public function delayDelete(Sdx_Db_Adapter $db = null)
	{
		if(!$db)
		{
			$db = $this->updateConnection();
		}

		$method_name = 'delete';
		$db->addSoftwareBasedSavePoint($this, $method_name);
	}

	/**
	 * Adapterからコールバックされる。手動で呼ぶ必要なし
	 * @param Sdx_Db_Adapter $db
	 * @param mixed $userdata
	 */
	public function onBeginTransaction(Sdx_Db_Adapter $db, $userdata)
	{
		$this->$userdata($db);
	}

	private function _getPkWhere(Sdx_Db_Adapter $db)
	{
		$where = '';
		foreach($this->_getTable()->getPkeys() as $pkey)
		{
			$where .= $db->quoteInto($pkey.' = ? AND ', $this->_get($pkey, ''));
		}
		return trim($where, ' AND ');
	}

	protected function _checkColumnExist($column)
	{
		if(!$this->_getTable()->hasColumn($column))
		{
			throw new Sdx_Db_Exception('Not exist column name is '.$column, $this);
		}
	}

	public function swapReferenceConnection(Sdx_Db_Adapter $db)
	{
		$this->_getTable()->swapReferenceConnection($db);
		return $this;
	}

	public function swapUpdateConnection(Sdx_Db_Adapter $db)
	{
		$this->_getTable()->swapUpdateConnection($db);
		return $this;
	}


	//TODO saveを呼び出してその中で自分を持っているレコードを保存した場合
	//無限ループになる。_saveで同じことをすると、保存する必要がないのに呼ばれる。
	//現在は上書がいた_saveのなかでparent::save()を呼ぶときにもう一度アップデイトが必要か
	//調べている。気軽にoverrideできるようにどうにかしたい。
	public function save(Sdx_Db_Adapter $db = null, $recursive = false)
	{
		/*if($this->isDeleted())
		{
			throw new Sdx_Db_Exception($this->toString().' record is already deleted.');
		}*/

		if(!$db)
		{
			$db = $this->updateConnection();
		}

		/**
		 * 10/02/16
		 * 例えばお店がメールをひとつ持ってる場合、shopにtelephone_idを持たせることになるが
		 * この場合
		 * $shop->setTelephone($tel);
		 * $shop->save();
		 * で両方保存されることが望ましい。普通はひとつしか持てないものを別テーブルにしないが
		 * 徹底的に正規化するならありえる。取りあえず、先に保存すると、問題が発生するまで
		 * 保存をするようにして置いて、例外を投げる方はコメントアウトしたままにしておく
		 *
		 * 10/02/17
		 * 転送のところでsaveを呼び出したrecordが最初に保存されないと、ややこしいことになることが判明
		 * 転送のやり方を変える方法もあるけど、そもそもsaveを呼び出したレコードが最初に保存される方が
		 * 直感的であるため、やっぱり、例外を投げるように変えます。
		 */


		//先に保存されるレコードが参照キーを持つタイプのリレーション
		//TYPE_MANY_ONE,TYPE_HAS_ONEの時は事前にリレーションのみをセーブする＞例外に変更
		//主キーが決まっていないと保存できないので
		$this->_callbackAllRelations(
			array($this, '_checkHasPkeyRelation'),
			$this->_relations,
			$db,
			$recursive
		);

		//↑のコメント参照念のためとっておきます
		/*if($this->_relation_save)
		{
			$this->_saveBeforeRelations($db);
		}*/

		if($this->_isNotDeleteAndUpdate())
		{
			$this->_save($db, $recursive);
		}

		if($recursive && $this->_relation_save)
		{
			$this->_saveAllRelations($db, $recursive);
		}

		return $this;
	}

	/**
	 * トランザクション突入前に呼ばれるとトランザクション突入直後にsaveされる
	 * @param Sdx_Db_Adapter $db
	 */
	public function delaySave(Sdx_Db_Adapter $db = null)
	{
		$this->_delay_save = true;

		if(!$db)
		{
			$db = $this->updateConnection();
		}
		$method_name = 'save';
		$db->addSoftwareBasedSavePoint($this, $method_name);
	}

	/*private function _saveBeforeRelations(Sdx_Db_Adapter $db)
	{
		if($this->_relations)
		{
			$this->_callbackAllRelations(
				array($this, '_saveHasPkeyRecord'),
				$this->_relations,
				$db
			);
		}
	}*/

	private function _toRecordList($target)
	{
		if($target instanceof Sdx_Db_Record_List)
		{
			return $target;
		}

		if($target instanceof Sdx_Db_Record)
		{
			return new Sdx_Db_Record_List(array($target));
		}

		if($target instanceof Sdx_Null)
		{
			return new Sdx_Db_Record_List();
		}
	}

	private function _saveAllRelations(Sdx_Db_Adapter $db, $recursive)
	{
		$this->_relation_save = false;

		//setterで入れ替えられたオブジェクトで古いものがあったら例外を投げるもの
		//TODO _callbackAllRelationsにまとめられないか？
		foreach($this->_exception_relations as $relname=>$relations)
		{
			$rel = $this->_getTable()->getRelation($relname);
			$rel_table = $rel->getRelationTable();
			$reference = $rel->getForeignColumn();

			$old_list = $this->_getOldRecords($rel, $rel_table, $reference, $db);
			$new_list = $this->_toRecordList($relations);

			$diff = $old_list->createDifferenceList($new_list);

			if($rel_table->isNotNull($reference))
			{
				if(count($diff) != 0)
				{
					throw new Sdx_Db_Exception(sprintf(
						'%s has other record for %s relation. Delete before set new record.',
						$this,
						$relname
					));
				}
			}
			else
			{
				foreach($diff as $old)
				{
					$old->set($reference, null);
					$old->save($db, $recursive);
				}
			}
		}

		$this->_exception_relations = array();

		//setterで入れ替えられたオブジェクトで古いものがあったら消してしまうもの
		//TODO _callbackAllRelationsにまとめられないか？
		//TODO _delete_relationsはManyManyだけなので$relationsがRecordであることを想定してない
		foreach($this->_delete_relations as $relname=>$relations)
		{
			$rel = $this->_getTable()->getRelation($relname);
			$rel_table = $rel->getRelationTable();
			$reference = $rel->getForeignColumn();

			$old_list = $this->_getOldRecords($rel, $rel_table, $reference, $db);
			$new_list = $relations;

			$delete_list = $old_list->createDifferenceList($new_list);
			$overlap_list = $old_list->createOverlapList($new_list);
			$newreocrd_list = $new_list->createDifferenceList($old_list);

			$delete_list->delete();

			$overlap_list->mergeList($newreocrd_list);
			$this->_relations[$relname] = $overlap_list;

		}

		$this->_delete_relations = array();

		//リレーションの更新
		if($this->_relations)
		{
			$this->_callbackAllRelations(
				array($this, '_saveRelationRecord'),
				$this->_relations,
				$db,
				$recursive
			);
		}

		//addされた一時リレーションの更新
		if($this->_tmp_relations)
		{
			$this->_callbackAllRelations(
				array($this, '_saveRelationRecord'),
				$this->_tmp_relations,
				$db,
				$recursive
			);
			$this->_tmp_relations = array();
		}
	}

	private function _getOldRecords(Sdx_Db_Relation $rel, Sdx_Db_Table $rel_table, $reference, Sdx_Db_Adapter $db)
	{
		$select = $rel_table->getSelect($db);

		$select->add(
			$reference,
			$this->getId(),
			$rel_table->getTableName()
		);

		return $rel_table->fetchAll($select);
	}

	/*private function _compareOddRecords(&$from, &$minus, $unset_minus)
	{
		if($from instanceof Sdx_Db_Record)
		{
			$from = array($from);
		}

		if($minus instanceof Sdx_Db_Record)
		{
			$minus = array($minus);
		}

		foreach($from as $fkey=>$frecord)
		{
			foreach($minus as $mkey=>$mrecored)
			{
				if($frecord->isEquals($mrecored))
				{
					unset($from[$fkey]);

					if($unset_minus)
					{
						//unset($minus[$mkey]);
					}
				}
			}
		}
	}*/

	/**
	 * 自分だけを保存。一部リレーションのみ、主キーが必要なので保存します
	 *
	 * @param Sdx_Db_Adapter $db
	 */
	protected function _save(Sdx_Db_Adapter $db, $recursive)
	{
		$this->_need_sync = true;

		$table = $this->_getTable()->getTableName();
		$now = $db->getCurrentTimestamp();

		//更新されていたらupdated_atを更新
		if($this->_getTable()->hasColumn('updated_at') && $this->_updates)
		{
			$this->_set('updated_at', $now);
		}

		//新規レコード
		if($this->_is_new)
		{
			//登録日時系をセット
			foreach(array('created_at', 'add_date', 'mod_date', 'updated_at') as $target)
			{
				if($this->_getTable()->hasColumn($target) && !$this->_get($target))
				{
					$this->_set($target, $now);
				}
			}

			//データを整形
			$data = $this->_updates;
			$this->_preNewSave($db, $data);
			$db->insert($table, $data);

			//autoincrementの主キーの場合はオブジェクトにセットする
			// TODO autoincrementは設定ファイルから取得するようにする?
			//（殆どがtrueなのでfalseの時のみ設定ファイルに書くとか）
			//何かうまい方法はないか？
			$pkeys = $this->_getTable()->getPkeys();
			if(count($pkeys) == 1 && $this->pkeyValue() === null)
			{
				$value = $db->lastInsertId($table, $pkeys[0]);
				$this->_set(
					$pkeys[0],
					$value
				);

				$data[$pkeys[0]] = $value;
			}

			//relationsに新しく決まった主キーをセット
			$this->_callbackAllRelations(
				array($this, '_setReferencesColumn'),
				$this->_relations,
				$db,
				$recursive
			);

			$this->_callbackAllRelations(
				array($this, '_setReferencesColumn'),
				$this->_tmp_relations,
				$db,
				$recursive
			);

			$this->_postNewSave($db, $data);

		}
		else //更新
		{
			//データを整形
			$data = $this->_updates;
			$this->_preUpdateSave($db, $data);
			$row_count = $db->update($table, $data, $this->_getPkWhere($db));
			
			if($row_count > 0)
			{
				$this->_postUpdateSave($db, $data, $row_count);
			}
		}

		//状態をリセット
		$this->_is_new = false;
		$this->_values = $this->toArray();
		$this->_updates = array();
		$this->_delay_save = false;
	}

	protected function _preNewSave(Sdx_Db_Adapter $db, array &$data)
	{
	}

	protected function _preUpdateSave(Sdx_Db_Adapter $db, array &$data)
	{
	}

	protected function _postNewSave(Sdx_Db_Adapter $db, array &$data)
	{
	}

	protected function _postUpdateSave(Sdx_Db_Adapter $db, array &$data, $row_count)
	{
	}

	/**
	 * 削除のときは参照されているレコードをチェック
	 * _callbackAllRelations用
	 */
	private function _checkReferenceRelationForDelete(Sdx_Db_Record $record, $rel_name)
	{
		$record->_unsetRecordFromRelation($this);
	}

	private function _unsetRecordFromRelation(Sdx_Db_Record $record)
	{
		$this->_scanRelationsForUnset($this->_relations, $record);
		$this->_scanRelationsForUnset($this->_tmp_relations, $record);
	}

	private function _scanRelationsForUnset(&$target_relations, Sdx_Db_Record $record)
	{
		foreach($target_relations as $rel_name=>$relations)
		{

			if(empty($relations))
			{
				continue;
			}

			if($relations instanceof Sdx_Db_Record)
			{
				if($record->isEquals($relations))
				{
					unset($target_relations[$rel_name]);
				}

				return;
			}

			foreach($relations as $innserkey=>$relation)
			{

				if($record->isEquals($relation))
				{
					unset($target_relations[$rel_name][$innserkey]);
					return;
				}
			}
		}
	}

	/**
	 * 例外を投げるよう、とりあえず、事前に保存で回避してます
	 * どっちが直感的か悩んでるのでとっておきます
	 * _callbackAllRelations用
	 */
	private function _checkHasPkeyRelation(Sdx_Db_Record $record, $rel_name)
	{
		$t_obj = $this->_getTable();
		$rel = $t_obj->getRelation($rel_name);
		if(
			$rel->getType() == Sdx_Db_Relation::TYPE_MANY_ONE
			||
			$rel->getType() == Sdx_Db_Relation::TYPE_HAS_ONE
		){
			//保存していたのをとりあえず例外に変更
			//$record->_save($db);
			//TODO　pkeyValuesじゃなくて大丈夫か？
			if($record->isNew() && !$record->pkeyValue())
			{
				throw new Sdx_Db_Exception(sprintf(
					'%s record don\'t have primary key yet. You must save before %s record save.',
					get_class($record),
					get_class($this)
				));
			}
		}
	}

	/**
	 * _callbackAllRelations用
	 */
	private function _setReferencesColumn(Sdx_Db_Record $record, $rel_name)
	{
		$rel = $this->getTable()->getRelation($rel_name);
		//多対多はセット不要
		if($rel->getType() == Sdx_Db_Relation::TYPE_MANY_MANY)
		{
			return;
		}
		$foreign = $rel->getReferenceColumn();
		$ref = $rel->getForeignColumn();

		$record->_set($ref, $this->_get($foreign));
	}

	/**
	 * _callbackAllRelations用
	 */
	private function _syncRecord(Sdx_Db_Record $record, $rel_name, Sdx_Db_Adapter $db)
	{
		if($record->_needSync())
		{
			$record->sync($db);
		}
	}

	/*private function _saveHasPkeyRecord(Sdx_Db_Record $record, $rel_name, Sdx_Db_Adapter $db)
	{
		$t_obj = $this->_getTable();
		$rel = $t_obj->getRelation($rel_name);
		if(
			$rel->getType() == Sdx_Db_Relation::TYPE_MANY_ONE
			||
			$rel->getType() == Sdx_Db_Relation::TYPE_HAS_ONE
		){
			if($record->isNew())
			{
				//自分だけを保存する
				$record->_save($db);
			}
		}
	}*/

	/**
	 * _callbackAllRelations用
	 */
	private function _saveRelationRecord(Sdx_Db_Record $record, $rel_name, Sdx_Db_Adapter $db, $recursive)
	{
		if($record->isDelaySave()) return;

		$record->save($db, $recursive);
	}

	private function _callbackAllRelations($callback, array $target_relations, Sdx_Db_Adapter $db, $recursive)
	{
		foreach($target_relations as $rel_name=>$relations)
		{

			if(empty($relations))
			{
				continue;
			}

			//一個しか持たないリレーションはいきなりオブジェクトが入ってます。
			if($relations instanceof Sdx_Db_Record)
			{
				$relations = array($relations);
			}

			foreach($relations as $relation)
			{
				$bool = call_user_func($callback, $relation, $rel_name, $db, $recursive);
			}
		}
	}

	public function beginTransaction(Sdx_Db_Adapter $db = null)
	{
		if(!$db)
		{
			$db = $this->updateConnection();
		}

		$db->beginTransaction();
	}

	public function commit(Sdx_Db_Adapter $db = null)
	{
		if(!$db)
		{
			$db = $this->updateConnection();
		}

		$db->commit();
	}

	public function rollback(Sdx_Db_Adapter $db = null)
	{
		if(!$db)
		{
			$db = $this->updateConnection();
		}

		$db->rollback();
	}

	public function __call($name, $arguments)
	{
		$key = Sdx_Util_String::decamelize($name);
		$trigger_error = true;

		if(strpos($key, 'get_') === 0)
		{
			$key = substr($key, 4);
			if(isset($this->_values[$key]))
			{
				return $this->_values[$key];
			}

			//return $this->_callNotExistGetter($name, $arguments, $key);
		}

		throw new Sdx_Db_Exception('Call to undefined method '.get_class($this).'::'.$name.'()');
		//trigger_error('Call to undefined method '.get_class($this).'::'.$name.'()');
	}

	public function toString()
	{
		$table = $this->_getTable();
		return $table->getClassSuffix().' #'.$this->get($table->getPkey());
	}

	/**
	 * 他DBとレコードをシンクロする。
	 * セーブされていないデータはシンクロしません。
	 */
	public function sync(Sdx_Db_Adapter $db, $check_exsist = true)
	{
		//セーブしたときに立てる
		$this->_need_sync = false;

		$table = $this->getTable();

		if($check_exsist)
		{
			$pkey = $this->pkeyValues();

			if(empty($pkey))
			{
				throw new Sdx_Exception('Sdx_Db_Record::sync is require primary key.');
			}


			$record = $table->findByPkey($pkey, $db);

			if($record instanceof Sdx_Null)
			{
				$record = $table->createRecord();

			}
		}
		else
		{
			$record = $table->createRecord();
		}

		$record->fromArray($this->toArray());
		$record->save($db, false);

		//リレーションの更新
		/*if($this->_relations)
		{
			$this->_callbackAllRelations(
				array($this, '_syncRecord'),
				$this->_relations,
				$db,
				false
			);
		}*/
	}

	/**
	 * 最初がSdx_Db_Select、2番目がSdx_Db_Adapterの配列を返す
	 * リレーションのgetterメソッドの最初の引数は両方の引数を受け取って自動判別する
	 *
	 * @param mixed $arg
	 * @param mixed $select_getter
	 * @return array
	 */
	protected function _detectGetterArg($arg, $select_getter)
	{
		//TODO 今はgetメソッドにDBが与えられたら強制的に取得するようになってます。
		//selectだったら強制取得・DBはスルーか？両方スルーの方が正しいか？
		if($arg instanceof Sdx_Db_Select)
		{
			$result[] = $arg;
			$result[] = $arg->getAdapter();
		}
		else if($arg instanceof Sdx_Db_Adapter)
		{
			$result[] = call_user_func(array($this, $select_getter), $arg);
			$result[] = $arg;
		}
		else if($arg === null)
		{
			$select = call_user_func(array($this, $select_getter));
			$result[] = $select;
			$result[] = $select->getAdapter();
		}
		else
		{
			throw new Sdx_Db_Exception('First argument must be instans of Sdx_Db_Select or Sdx_Db_Adapter.');
		}

		return $result;
	}

	public function __toString()
	{
				return $this->toString();
		}

		protected function _getUnserializeValueWithKey($name, $key, $default = null)
		{
			$values = $this->_getUnserializedValue($name);

			if(!isset($values[$key]))
			{
				return  $default;
			}

			return $values[$key];
		}

	/**
	 * @deprecated カラムがNULLだった時に、NULLをキャッシュに入れてしまい、
	 * 事実上キャッシュされない、空の場合はNULLが変えるのが自然なので_getUnserializedValuesの方を使用してください。
	 **/
	protected function _getUnserializedValue($name)
	{
		if(!isset($this->_unserialize_value[$name]))
		{
			$this->_unserialize_value[$name] = json_decode($this->_get($name), true);
		}

		return $this->_unserialize_value[$name];
	}

	protected function _getUnserializedValues($name)
	{
		if(!isset($this->_unserialize_value[$name]))
		{
			$values = json_decode($this->_get($name), true);
			$this->_unserialize_value[$name] = $values ? $values : array();
		}

		return $this->_unserialize_value[$name];
	}

	protected function _setSerializedValue($name, $value)
	{
		$this->_unserialize_value[$name] = $value;
		$encoded = Sdx_Util_String::getJsonEncodedValue($this->_unserialize_value[$name]);

		return $this->_set($name, $encoded);
	}

	private function _hasUpdateColumns()
	{
		//$up_dates内にテーブル毎に必要なカラムが存在すれば更新と認識

		if(!$this->_updates) return false;

		foreach($this->_updates as $key => $value)
		{
			if($this->getTable()->hasColumn($key))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * 保存されるデータの中で変更されたカラム名の一覧を返す
	 * @return array
	 */
	public function updatedColumns()
	{
		$result = array();
		if(is_array($this->_updates))
		{
			foreach($this->_updates as $key => $value)
			{
				if($this->getTable()->hasColumn($key))
				{
					$result[] = $key;
				}
			}
		}
		return $result;
	}

	private function _isNotDeleteAndUpdate()
	{
		if(!$this->_is_deleted && ($this->_hasUpdateColumns() || $this->_is_new))
		{
			return true;
		}

		return false;
	}

	protected function isDelaySave()
	{
		return $this->_delay_save;
	}

	public function setBitFlagFromArray($column, array $flags)
	{
		$bit_flag = 0;

		foreach($flags as $flag)
		{
			$bit_flag = $bit_flag | $flag;
		}

		$this->set($column, $bit_flag);

		return $this;
	}

	public function getArrayFromBitFlag($column)
	{
		$result = array();
		$value = $this->get($column);

		if(!$value instanceof Sdx_Null)
		{
			if(!is_numeric($value))
			{
				throw new Sdx_Exception(sprintf(
					'%s column on %s table for (%s) is not numeric.',
					$column,
					$this->getTable()->getTableName(),
					var_export($this->pkeyValues, false)
				));
			}

			$value = (int) $value;
			$i = 1;

			while($value > 0)
			{
				if(($value & 1) === 1)
				{
					$result[] = $i;
				}

				$i *= 2;
				$value = $value >> 1;
			}
		}

		return $result;
	}

	public function isNull()
	{
		return false;
	}
	
	/**
	 * レコードの内容をdumpする。自分の持ってるカラムのみ。
	 * @param bool $display_methods クラスのインスタンスだった場合メソッドも出力するかどうか。
	 */
	public function dump($display_methods = false)
	{
		$dump = array('values'=>$this->toArray());
		
		if($display_methods)
		{
			$dump['methods'] = $this;
		}
		
		Sdx_Debug::dump($dump, get_class($this));
	}
}
