<?php
class hongbaoController {
	function getHongbaoListCon(){
		VIEW::assign(array(
			'retArr' => M('hongbao')->getHongbaoInfoMod()
		));

		VIEW::display('admin/forHongbao/hongbaoInfoSearch.html');

	}

}