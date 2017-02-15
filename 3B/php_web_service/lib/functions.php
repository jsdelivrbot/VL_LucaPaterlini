<?php

    function move($ndisks, $startPeg, $endPeg, &$tmp_v, &$return_list){
        // move: moves a pile of shapes from the source color to the target color
        if ($ndisks>0) {
            move($ndisks - 1,$startPeg, 3-$startPeg-$endPeg,$tmp_v,$return_list);
            $element=-array_search($startPeg,array_reverse($tmp_v))-1;
            $tmp_v[count($tmp_v)+$element]=$endPeg;
            array_push($return_list,[$startPeg, $endPeg,abs($element+1)]);
            move($ndisks - 1,3-$startPeg-$endPeg,$endPeg,$tmp_v,$return_list);
        }
    }
    function hanoiMoves($vitamin,&$tmp_v,$size){
        // hanoiMoves: starts from the final stage searching where to put the next disk from the bottom of the pile
        $return_values=[];
        for($i=$size-1;$i>-1;$i--){
            $t = $size-1-$i;
            if($vitamin[$t]==$tmp_v[$t]){continue;}
            if ($i>0){
                move($i,$tmp_v[$t], 3-$vitamin[$t]-$tmp_v[$t],$tmp_v,$return_values);
            }

            array_push($return_values,[$tmp_v[$t],$vitamin[$t], $i]);
            $tmp_v[$t]=$vitamin[$t];
        }
        return $return_values;
    }
    function reverseMovesAddNames($l,$names,$s_vitamin){
        // reverseMovesAddNames: changes the order and add the proper names to the array of the moves
        $result = [];
        $len_names=count($names);
        foreach (array_reverse($l) as &$item) {
            $tmp = [$names[$len_names-1 - $item[2]], $s_vitamin[$item[1]], $s_vitamin[$item[0]]];
            array_push($result,$tmp);
        }
        unset($item);
        return $result;
    }
    function makeAllWhite($Vitamin_String){
        # makeAllWhite: return the array of moves needed to set all of the colors
        # of the shapes to white accordingly with the Maxi-Maxi Principle.

        //$Vitamin_String=trim($Vitamin_String);
        $Vitamin_String=trim($Vitamin_String);
        $colors =  "BGW";
        $tmp_v =[];$names_v = [];

        $to_loop=explode(" ", $Vitamin_String);
        foreach ($to_loop as &$item){
            $value = intval(substr($item,0,-1));
            $color = substr($item,-1,1);
            array_push($tmp_v,strpos($colors,$color));
            array_push($names_v,$value);
        }
        unset($item);

        $size=count($tmp_v);
        $h_array= array_fill(0, $size, 2);

        $move_list = hanoiMoves($tmp_v,$h_array,$size);
        return reverseMovesAddNames($move_list,$names_v,$colors);
    }
    function makeAllWhiteStatus($s_vitamins,&$list_moves)
    {
        // makeAllWhiteStatus: take as input the start status and the moves and return and array
        //                     with the list of status generated by the moves
        $s_vitamins=trim($s_vitamins);
        $s_vitamin_split=explode(" ",$s_vitamins);
        $key=array_map(function($item){ return intval(substr($item, 0, -1));},$s_vitamin_split);
        $value=array_map(function($item){ return substr($item,-1,1);},$s_vitamin_split);
        $tmp_dict=array_combine($key,$value);
        $result=[$s_vitamins];

        foreach ($list_moves as &$c){
            $tmp_dict[$c[0]] = $c[2];
            array_push($result,join(' ',array_map(function($k) use ($tmp_dict) {return strval($k).$tmp_dict[$k]; },array_keys($tmp_dict))));
        }
        unset($c);
        return $result;
    }

?>