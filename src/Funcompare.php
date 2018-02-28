<?php

/*
 * This file is part of the funsoul/funcompare.
 *
 * (c) funsoul <funsoul.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Funsoul\Funcompare;
/*
 * A tool compare text differences.
 *
 * @author    funsoul <funsoul.org>
 * @copyright 2018 funsoul <funsoul.org>
 *
 * @link      https://github.com/funsoul/funcompare
 * @link      http://funsoul.org
 */
use InvalidArgumentException;

Class Funcompare
{
    private $_old_l_wrapper = '<span class="old-word">';
    private $_old_r_wrapper = '</span>';
    private $_new_l_wrapper = '<span class="new-word">';
    private $_new_r_wrapper = '</span>';

    public function compareText($oldString, $newString)
    {
        $oldArr = preg_split('/\s+/', $oldString);
        $newArr = preg_split('/\s+/', $newString);
        $resArr = array();

        $oldCount = count($oldArr) - 1;
        $tmpOldIndex = 0;
        $tmpNewIndex = 0;
        $end = false;

        while(!$end){
            if($tmpOldIndex <= $oldCount){
                if(isset($oldArr[$tmpOldIndex]) && isset($newArr[$tmpNewIndex]) && $oldArr[$tmpOldIndex] === $newArr[$tmpNewIndex]){
                    array_push($resArr, $oldArr[$tmpOldIndex]);
                    $tmpOldIndex++;
                    $tmpNewIndex++;
                }else{
                    $foundKey = array_search($oldArr[$tmpOldIndex], $newArr,TRUE);
                    if($foundKey != '' && $foundKey > $tmpNewIndex){
                        for($p=$tmpNewIndex; $p<$foundKey; $p++){
                            array_push($resArr,$this->_new_l_wrapper.$newArr[$p].$this->_new_r_wrapper);
                        }
                        array_push($resArr, $oldArr[$tmpOldIndex]);
                        $tmpOldIndex++;
                        $tmpNewIndex = $foundKey + 1;
                    }else{
                        array_push($resArr,$this->_old_l_wrapper.$oldArr[$tmpOldIndex].$this->_old_r_wrapper);
                        $tmpOldIndex++;
                    }
                }
            }else{
                $end = true;
            }
        }

        $textFinal = '';
        foreach($resArr as $val){
            $textFinal .= $val.' ';
        }
        return $textFinal;
    }

    public function compareJson($oldJson, $newJson)
    {
        $oldArr = json_decode($oldJson,true);
        $newArr = json_decode($newJson,true);
        $res = $this->checkDiffMulti($oldArr,$newArr);
        return json_encode($res,JSON_UNESCAPED_SLASHES);
    }

    private function checkDiffMulti($array1, $array2){
        $result = array();

        foreach($array1 as $key => $val) {
            if(is_array($val) && isset($array2[$key])) {
                $tmp = $this->checkDiffMulti($val, $array2[$key]);
                if($tmp) {
                    $result[$key] = $tmp;
                }
            }
            elseif(!isset($array2[$key])) {
                $result[$key] = null;
            }
            elseif($val !== $array2[$key]) {
                $result[$key]['old'] = $this->_old_l_wrapper.$val.$this->_old_r_wrapper;
                $result[$key]['new'] = $this->_new_l_wrapper.$array2[$key].$this->_new_r_wrapper;
            }

            if(isset($array2[$key])) {
                unset($array2[$key]);
            }
        }
        return array_merge($result, $array2);
    }

    public function wrapper($oldLeftWrapper, $oldRightWrapper, $newLeftWrapper, $newRightWrapper){
        $this->_old_l_wrapper = $oldLeftWrapper;
        $this->_old_r_wrapper = $oldRightWrapper;
        $this->_new_l_wrapper = $newLeftWrapper;
        $this->_new_r_wrapper = $newRightWrapper;
        return $this;
    }
}

$old = '[{"id":1,"name":"xxx","age":18,"cart":[{"id":100,"name":"rice"}]},{"id":2,"name":"aaa","age":18}]';
$new = '[{"id":1,"name":"yyy","age":20,"cart":[{"id":100,"name":"banana"}]},{"id":2,"name":"bbb","age":18}]';

$fc = new Funcompare();
$res = $fc->compareJson($old, $new);
echo $res.chr(10);

$old = 'A tool compare text differences is funny';
$new = 'A tool that compare text differences';

$fc = new Funcompare();
$res = $fc->wrapper('[',']','<','>')->compareText($old, $new);
echo $res;