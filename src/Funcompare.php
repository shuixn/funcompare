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

    public function compare($oldString, $newString)
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
                if($oldArr[$tmpOldIndex] === $newArr[$tmpNewIndex]){
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

    public function wrapper($oldLeftWrapper, $oldRightWrapper, $newLeftWrapper, $newRightWrapper){
        $this->_old_l_wrapper = $oldLeftWrapper;
        $this->_old_r_wrapper = $oldRightWrapper;
        $this->_new_l_wrapper = $newLeftWrapper;
        $this->_new_r_wrapper = $newRightWrapper;
        return $this;
    }
}