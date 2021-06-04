<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;
use app\admin\model\Rulegroup as RulegroupModel;
use lib\Validata;
class Rulegroup extends Basic
{
	
	
    public function index(Request $request)
    {	
    	$where = '';
		$list = RulegroupModel::getList($where);
		// 获取分页显示
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('page_title','权限角色组');
       	return $this->fetch();
    }

    
	
	public function add(){
	
		$validata = new Validata();
		//防重复提交
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('page_title','添加角色');
       	return $this->fetch();
	}
	
	public function add_save(Request $request){
		$_param = $request->param();
		$_param['status'] = 1;
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_param['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_param['token']);
		$res = RulegroupModel::saveData($_param);
		if($res){
			$this->success('添加成功', 'Rulegroup/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
	}
	
	
	
	public function  edit(Request $request){
		
	
		$id = $request->param('id');
		if($id >  0){
			$res = Db::name('auth_group')->where('id',$id)->find();
		}
		//防重复提交
		$validata = new Validata();
		$token = $validata->set_token();
		$this->assign('token',$token);
		//
		$this->assign('_data', $res);
		$this->assign('page_title','角色编辑');
		return $this->fetch();
	}
	
	
	
	public function edit_save(Request $request){
		
		
		$_params = $request->param();
	
		//echo $_param['token'];
		//防重复提交
		$validata = new Validata();
		if(!$validata->valid_token($_params['token'])){
			$this->error('请勿重复提交!');
			exit();
		}
		unset($_params['token']);
		//
		$res = RulegroupModel::editsaveData($_params);
		if($res){
			$this->success('修改成功', 'Rulegroup/index');
			exit();
		}else{
			$this->error('系统繁忙,请稍候再试!');
			exit();
		}
		
	}
	
	public function delete(Request $request){
		$request_id = $request->param('id'); 
		$res = Db::name('auth_group')->delete($request_id);
		if($res){
			$this->success('删除成功', 'Rulegroup/index');
		}else{
			$this->error('删除失败');  
		}
	}
	
	
	
	public function rule_distribution(Request $request){
        if(Request::instance()->post()){
			$data = $request->param();
            $datas['rules']=implode(',', $data['rule_ids']);
            $result=Db::name('auth_group')->where(['id'=>$data['id']])->update($datas);
            // $result=Db::name('auth_group')->editData($map,$data);
            if ($result) {
                $this->success('操作成功','Rulegroup/index');
            }else{
                $this->error('操作失败');
            }
        }else{
			$id = $request->param('id');
            $group_data=Db::name('auth_group')->where(array('id'=>$id))->find();
            $group_data['rules']=explode(',', $group_data['rules']);
            // 获取规则数据
            $data = Db::name('auth_rule')->order('id asc')->select();
			$tree = $this->getTree($data);
			$this->assign('rule_data', $tree);
			$this->assign('group_data', $group_data);
			$this->assign('page_title','分配权限');
            return $this->fetch();
        }
    }
	
	
	public function getTree($list)
    {
		
		$tree = [];
		foreach($list as $key =>$val){
			if($val['pid'] ==0){
				$tree[] = $val;	
			}else{
				foreach($tree as $k =>$v){
					if($val['pid']==$v['id']){
						$tree[$k]['child'][] = $val;
					}else{
						//
						if(isset($tree[$k]['child'])){
							foreach($tree[$k]['child'] as $kk => $vv){
								if($val['pid']==$vv['id']){
									$tree[$k]['child'][$kk]['child'][] = $val;
								}
							}
						}
						//
					}
				}
			}
		}
		return $tree;
    }
	
	
	
    
}