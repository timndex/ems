<?php
namespace app\models\Patches;

use Yii;
use app\models\Menu;
use app\models\MenuSubcategory;
use yii\helpers\Url;
use app\models\Accounts;

class Patch 
{

public static function randomNumbers(){

$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 
 function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}
	return generate_string($permitted_chars, 20);
	// echo $value;

}

// public function invoicenum($input, $strength = 16) {
//     $input_length = strlen($input);
//     $random_string = '';
//     for($i = 0; $i < $strength; $i++) {
//         $random_character = $input[mt_rand(0, $input_length - 1)];
//         $random_string .= $random_character;
//     }
 
//     return $random_string;
// }
// 	return generate_string($permitted_chars, 20);
// 	// echo $value;

// }

public function Menus(){
			 $item = [];
			 // $model->menu_active_department = implode(',', $model->menu_active_department);
			 $accounts = Accounts::find()->all();
			 foreach ($accounts as $value) {
			 	$user_department = $value['user_department'];


}
	      	 $Category = Menu::find()->where(['status'=>10])->orderBy(['menu_name'=>SORT_ASC])->all();
	         $count=0;
	     	 foreach($Category as $model) {
	     	 	$departments = array();
	     	 	$departments = explode(",", $model['menu_active_department']);
	     	 	foreach ($departments as  $value) {
	     	 		$menu_active = $value;
	     	 		
	     	 	if(\Yii::$app->user->identity->user_department == $menu_active){
	     	 	
	         $item[] = ['icon'=>$model->menu_icon, 'label' => $model->menu_name, 'url' => $model->menu_url];

	         $Subcat = MenuSubcategory::find()->where(['menu_id'=>$model->menu_id])->all();

	          $cnt=0;
	          foreach($Subcat as $model2) {
	           $item[$count]['items'][$cnt] = ['label' => $model2->subcategory, 'url' => $model2->subcategory_url];
	           $cnt++;
	       }
	            $count++;
	       }
	   }
	}
	       return $item;


}


}