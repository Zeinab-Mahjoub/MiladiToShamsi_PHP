<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <?php
    if (isset($_POST['calculateBtn'])) {
        
    }
    ?>
</head>
<body>
<div class="container">
    <div class="row text-center text-muted">
        <h1 class="py-5">تبدیل تاریخ میلادی به شمسی</h1>
    </div>
    <div class="row">
        <form action="" method="post">
            <div class="input-group mb-3 w-75 mx-auto">
                <label for="input" class="w-100 text-center pb-3 text-muted">: تاریخ میلادی مورد نظر را انتخاب نمایید</label>
                <input name="entry-date" id="input" type="date" class="form-control">
                <input class="btn btn-outline-primary btn" name="calculateBtn" value="تبدیل به شمسی" type="submit" id="button-addon2">
            </div>
        </form>
    </div>
    <div class="row output pt-5 text-center">
        <p class="text-dark">
            <?php
            define('G_SH_Diff_D', 226899);
            if (isset($_POST['calculateBtn']) AND !empty($_POST['entry-date'])) {
                $dateInGreg = date_parse($_POST['entry-date']);
//                $dateInGreg is a 2D array: 'year' , 'month' , 'day', all Nos are int, w/out extra zeros.
//              ===================  Greg Leap check  ======================
                if ($dateInGreg['year'] % 4 == 0) {
                    if ($dateInGreg['year'] % 100 != 0) {
                        $G_Leap = true;
                    }
                    else {
                        if ($dateInGreg['year'] % 400 == 0) {
                            $G_Leap = true;
                        } else {
                            $G_Leap = false;
                        }
                    }
                } else {
                    $G_Leap = false;
                }
                define('G_M_Days_count', array(
                    'Jan' => 31,
                    'Feb' => $G_Leap ? 29 : 28,
                    'Mar' => 31,
                    'Apr' => 30,
                    'May' => 31,
                    'Jun' => 30,
                    'Jul' => 31,
                    'Aug' => 31,
                    'Sep' => 30,
                    'Oct' => 31,
                    'Nov' => 30,
                    'Dec' => 31
                ));
                if ($dateInGreg['month'] >= 1 AND $dateInGreg['month'] <= 3) {
                    if (($dateInGreg['month'] == 1 OR $dateInGreg['month'] == 2) || ($dateInGreg['month'] == 3 AND $dateInGreg['day'] <= 21)) {
                        $G_SH_Diff_Y = 622;
                    } else {
                        $G_SH_Diff_Y = 621;
                    }
                } else {
                    $G_SH_Diff_Y = 621;
                }
                $Year_To_Sh = $dateInGreg['year'] - $G_SH_Diff_Y;
//                ===================  Shamsi Kabise Check  ======================
                if ($Year_To_Sh % 4 == 3) {
                    $Sh_Leap = true;
                } else {
                    $Sh_Leap = false;
                }
                $total_Year_D_count = $Sh_Leap ? 366 : 365;
                define('Sh_M_Days_count', array(
                    'Far' => 31,
                    'Ord' => 31,
                    'Kho' => 31,
                    'Tir' => 31,
                    'Mor' => 31,
                    'Sha' => 31,
                    'Meh' => 30,
                    'Aba' => 30,
                    'Aza' => 30,
                    'Dey' => 30,
                    'Bah' => 30,
                    'Esf' => $Sh_Leap ? 30 : 29
                ));
                $G_Leap_Y_count_NOT = $dateInGreg['year'] / 4;
                $G_Leap_Y_count = $G_Leap ? ((int) $G_Leap_Y_count_NOT) - 1 : (int) $G_Leap_Y_count_NOT;
                $Sh_Kbs_Y_count_NOT = ($Year_To_Sh / 4);
                $Sh_Kbs_Y_count = (int) $Sh_Kbs_Y_count_NOT;
                $whole_M_count = $dateInGreg['month'] - 1;
                $whole_Months = array_slice(G_M_Days_count, 0, $whole_M_count);
                $whole_Months_D_count = 0;
                foreach ($whole_Months as $month => $dayCount) {
                    $whole_Months_D_count += ($dayCount);
                }
                $All_Months_D_count = $whole_Months_D_count + $dateInGreg['day'];
                $G_Days_Past = ($dateInGreg['year'] -1) * 365 + $G_Leap_Y_count + $All_Months_D_count;
                $Sh_Days_Past = $G_Days_Past - G_SH_Diff_D;
                $Sh_Days_Not_Kbs = $Sh_Days_Past - $Sh_Kbs_Y_count;
                $Sh_Date = $Sh_Days_Not_Kbs % 365 == 0 ? 365 : $Sh_Days_Not_Kbs % 365;
                $Sh_M_counter = 1;
                foreach (Sh_M_Days_count as $month => $dayCount) {
                    $Sh_M_counter += 1;
                    $Sh_Date -= $dayCount;
                    if ($Sh_Date > 0) {
                        if ($Sh_Date == $total_Year_D_count) {
                            $Sh_M_counter = 12;
                            $Sh_Date = Sh_M_Days_count['Esf'];
                            break;
                        }
                        if ($Sh_Date == 1) {
                            $Sh_M_counter = 12;
                            $Year_To_Sh += 1;
                            $Sh_Date = Sh_M_Days_count['Esf'];
                            break;
                        }
                    }
                    if ($Sh_Date == 0) {
                        $Sh_M_counter -= 1;
                        $Sh_Date = $dayCount;
                        if (($Year_To_Sh >= 1343 && $Year_To_Sh <= 1374) && $Sh_Leap && $Sh_M_counter == 12) {
                            $Sh_Date -= 1;
                        }
                        break;
                    }
                    if ($Sh_Date < 0) {
                        $Sh_M_counter -= 1;
                        $Sh_Date += $dayCount;
                        if (($Year_To_Sh >= 1343 && $Year_To_Sh <= 1374) && $Sh_Leap && $Sh_M_counter == 12) {
                            $Sh_Date -= 1;
                        }
                        break;
                    }
                }
            ?>
                <span>تاریخ میلادی</span><span> </span><span class="original-date"><?php                 echo $_POST['entry-date']; ?></span><span> </span><span>در تقویم شمسی، معادل</span><span> </span><span class="transformed-date text-success"><?php echo $Year_To_Sh . '/' . $Sh_M_counter . '/' . $Sh_Date; ?></span><span> </span><span class="text-start">می باشد</span>
            <?php } ?>
        </p>
    </div>
</div>
</body>
</html>
