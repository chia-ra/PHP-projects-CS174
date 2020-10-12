<?php
  echo <<<_END
    <html><head><title>PHP Form Upload</title>
    </head><body><form method='post' action='findGreatestProduct.php' enctype='multipart/form-data'>
      Select .txt File:
      <input type='file' name='filename' size='10'>
      <input type='submit' value='Upload'>
    </form>
    _END;
    if ($_FILES) {
      $name = $_FILES['filename']['name'];

      if($_FILES['filename']['type'] == 'text/plain')
      {
        $fh = fopen("$name", 'r') or die ("Failed to open file");
        //if (flock($fh, LOCK_EX)) {
        $contents = file_get_contents("$name");
          //flock($fh, LOCK_UN);
        //}
        fclose($fh);
        validate($contents);
        //$num = solve($array2d);
        //echo  "Max number is $num <br>";
      }
      else
      {
        echo "File must be in .txt format. <br>";
      }
    }
    echo "</body></html>";


    function validate($input_String) {
      $str = preg_replace('/\\s/','',$input_String);
      //echo $str;
      if (strlen($str) < 400) {
        echo "Not enough numbers in file! <br>";
        return 0;
      }
      else {
        $arr2D = array_fill(0, 20, array_fill(0, 20, null));
        //$temp_arr = str_split($str);
        $temp = str_split($str);
        $k=0;
        for ($i=0; $i<20; $i++) {
          for ($j = 0; $j < 20; $j++) {
            $arr2D[$i][$j]= $temp[$k];
            $isNumber=is_numeric($arr2D[$i][$j]);
            if($isNumber==false){
              echo "File must be numeric only. <br>";
              return 0;
            }
            $k++;
          }
        }
        solve($arr2D);
      }
    }

    function solve($arr)
    {
      $product = 0;
      $productNums = array_fill(0, 4, 0);
      for ($row = 0; $row < 20; $row++)
      {
        for($col = 0; $col < 20; $col++)
        {
          if($col < 20 - 3)
          { //horizontal
            if(($arr[$row][$col]* $arr[$row][$col + 1]* $arr[$row][$col + 2]* $arr[$row][$col + 3]) > $product)
            {
              $product = ($arr[$row][$col]* $arr[$row][$col + 1]* $arr[$row][$col + 2]* $arr[$row][$col + 3]);
              $productNums[0] = $arr[$row][$col];
              $productNums[1] = $arr[$row][$col + 1];
              $productNums[2] = $arr[$row][$col + 2];
              $productNums[3] = $arr[$row][$col + 3];
            }
          }

          if($row < 20 - 3)
          { //vertical
            if(($arr[$row][$col]* $arr[$row + 1][$col]* $arr[$row + 2][$col]* $arr[$row + 3][$col]) > $product)
            {
              $product = ($arr[$row][$col]* $arr[$row + 1][$col]* $arr[$row + 2][$col]* $arr[$row + 3][$col]);
              $productNums[0] = $arr[$row][$col];
              $productNums[1] = $arr[$row + 1][$col];
              $productNums[2] = $arr[$row + 2][$col];
              $productNums[3] = $arr[$row + 3][$col];
            }

            if($col < 20 - 3 )
            { //diagonal 1
              if(($arr[$row][$col]* $arr[$row + 1][$col + 1]* $arr[$row + 2][$col + 2]* $arr[$row + 3][$col + 3]) > $product)
              {
                $product = ($arr[$row][$col]* $arr[$row + 1][$col + 1]* $arr[$row + 2][$col + 2]* $arr[$row + 3][$col + 3]);
                $productNums[0] = $arr[$row][$col];
                $productNums[1] = $arr[$row + 1][$col + 1];
                $productNums[2] = $arr[$row + 2][$col + 2];
                $productNums[3] = $arr[$row + 3][$col + 3];
              }
            }

            if($col > 3)
            { //diagonal 2
              if(($arr[$row][$col]* $arr[$row + 1][$col - 1]* $arr[$row + 2][$col - 2]* $arr[$row + 3][$col - 3]) > $product)
              {
                $product = ($arr[$row][$col]* $arr[$row + 1][$col - 1]* $arr[$row + 2][$col - 2]* $arr[$row + 3][$col - 3]);
                $productNums[0] = $arr[$row][$col];
                $productNums[1] = $arr[$row + 1][$col - 1];
                $productNums[2] = $arr[$row + 2][$col - 2];
                $productNums[3] = $arr[$row + 3][$col - 3];
              }
            }
          }
        }
      }
      echo  " Max number is $product ";
      echo " From $productNums[0],$productNums[1],$productNums[2],$productNums[3] <br>";
      //return $product;
    }

    function tester() {
      echo "<br><b><u>Test 1: </u></b>  Expected Answer: Max 1 from 1,1,1,1 <br>";
      validate("0000000000000000000000000000011110000000000000000000000000000000000000000000000000000000000000000000000000000000000
      00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
      00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
      00000000000000000000000000000000000");

      echo "<br><b><u>Test 2: </u></b>  Expected Answer: Max 5832 from 9,9,8,9 <br>";
      validate("7163626956188267042885861560789112949495657273330010533678815258490771167055601353697817977846174064839722413756570
      56057821663704844031998909698352031277450632612540698747158523863668966489504452445230588611646710940507716427171479924442928
      17866458359124566529242190226710556263210719840385096245544484580156166097919133622298934233803081357316717653133062491930358
      90729629049156070172427121883998797");

      echo "<br><b><u>Test 3: </u></b>  Expected Answer: Failure; \"File must be numeric only.\" <br>";
      validate("00000000000000000000000000000jgpq0000000000000000000000000000000000000000000000000000000000000000000000000000000000
      00000000000000000111100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
      00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
      00000000000000000000000000000000000");

      echo "<br><b><u>Test 4: </u></b>  Expected Answer: Failure; \"Not enough numbers in file!\" <br>";
      validate("0000000000000000000000000000011110000000000000000000000000000000000000000000000000000000000000000000000000000000000");

    }
    tester();
?>
