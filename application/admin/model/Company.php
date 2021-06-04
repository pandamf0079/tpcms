<?php

namespace app\admin\model;
use think\Db;
use think\Model;
use think\Config;
class Company extends Model
{
 
	//protected $table = 'borrow';
 
 	
	
	
	
	
	public static function saveData($data){
		Db::startTrans();
		try{
			Db::name('company')->where('id', $data['id'])->update($data);
			// 提交事务
			Db::commit();
			return true;    
		} catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
			return false;
		}
	}
	
	

	
	/*$rows = empty($where['limit']) ? Config::get('paginate.list_rows') : $where['limit'];

        $list = self::
        field('p.*,pc.catename,pc.thumb,pc.minprice,pc.maxprice,pc.adopt_time,pc.appointment_integral,pc.adopt_integral,pc.cycle,pc.figure,pc.bst_currency,pc.ocn_figure,pl.levelname')
        ->alias('p')
        ->join('product_cate pc','p.cate_id = pc.id')
        ->join('product_level pl','pc.level_id = pl.id')
        ->order('p.id desc')
        ->where($where)
        ->paginate($rows,false,['query'=>$where])->each(function($item){
            $config = Config::get('payconfig');
            $item['thumb'] = url($item['thumb'], '', '', true);
            $item['isadopt'] = isAdopt($item['adopt_time']);
            $item['figure'] = intval($item['figure']);
            if(!$item['owner_mid']){
                $item['owner'] = $config['nickname'];
            }else{
                $member = Member::getMember(['mid' => $item['owner_mid']]);
                $item['owner'] = $member['nickname'];
            }
            if(!$item['transferor_mid']){
                $item['transferor'] = $config['nickname'];
            }else{
                $member = Member::getMember(['mid' => $item['transferor_mid']]);
                $item['transferor'] = $member['nickname'];
            }
        })->toArray()?:[];

        return $list;*/
 
 
}


?>