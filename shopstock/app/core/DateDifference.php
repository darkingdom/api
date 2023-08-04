<?php
class DateDifference
{
    public
        $diff = 0;

    public function __construct($start, $end)
    {
        $start  = strtotime($start);
        $end    = strtotime($end);
        $this->diff = $end - $start;
    }

    public function day()
    {
        return floor($this->diff / 86400);
    }

    public function hour()
    {
        $hitung   = $this->diff % 86400;
        return floor($hitung / 3600);
    }
    public function minute()
    {
        $hitung   = $this->diff % 86400;
        $hitung   = $hitung % 3600;
        return floor($hitung / 60);
    }
    public function second()
    {
        $hitung   = $this->diff % 86400;
        $hitung   = $hitung % 3600;
        $hitung   = $hitung % 60;
        return floor($hitung / 1);
    }

    public function all()
    {
        if ($this->day() > 0) {
            return "{$this->day()} hari {$this->hour()} jam";
        } else {
            if ($this->hour() > 0) {
                return "{$this->hour()} jam {$this->minute()} menit";
            } else {
                return "{$this->minute()} menit";
            }
        }
    }
}



/* 
========================================================

HOW TO USE :

EXAMPLE 1:
$date1 = '2020-06-24 13:31:26';
$date2 = '2020-06-25 06:43:15';
$date = new DateDifference($date1, $date2);
echo $date->day();
echo '<br/>';
echo $date->hour();
echo '<br/>';
echo $date->minute();
echo '<br/>';
echo $date->second();


EXAMPLE 2:
$date1 = date('Y-m-d H:i:s');
$date2 = $transaksi->limit_date;
if ($date2 > $date1) {
    $date = new DateDifference($date1, $date2);
    if ($date->day() > 0) {
        echo "{$date->day()} hari {$date->hour()} jam";
    } else {
        if ($date->hour() > 0) {
            echo "{$date->hour()} jam {$date->minute()} menit";
        } else {
            echo "{$date->minute()} menit";
        }
    }
}

========================================================
*/