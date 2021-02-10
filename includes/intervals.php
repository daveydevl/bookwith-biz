<?php

function overlap($a, $b) {
    $x = 0;
    $y = 0;
    $c = [];
    $sizeA = sizeof($a);
    $sizeB = sizeof($b);
    
    while ($x < $sizeA) {
        $ax = $a[$x];
        $by = $b[$y];
        
        if ($ax[1] <= $by[0]) {
            $x++;
            $y = $y == 0 ? $y : $y - 1;
        } elseif ($ax[0] >= $by[1])
            $y++;
        else {
            $c[] = [
                max($ax[0], $by[0]), 
                min($ax[1], $by[1])
            ];
            $y++;
        }
        
        if ($y >= $sizeB) {
            $x++;
            $y--;
        }
    }
    
    return $c;
}

function inverse($a, $start, $end) {
    $b = [];
    
    $sizeA = sizeof($a);
    if ($sizeA == 0) {
        $b[] = [$start, $end];
        return $b;
    }
    
    $first = $a[0];
    $last  = $a[$sizeA - 1];
    
    //insert first free slot
    if ($first[0] != $start)
        $b[] = [$start, $first[0]];
    
    //insert middle slots
    for ($i = 0; $i < $sizeA - 1; $i++) {
        $start_slot = $a[$i][1];
        $end_slot   = $a[$i + 1][0];
        
        if ($start_slot != $end_slot) //if slots are next to each other, skip it
            $b[] = [$start_slot, $end_slot];
    }
    
    //insert last free slot
    if ($last[1] != $end)
        $b[] = [$last[1], $end];
    
    return $b;
}

function can_fit($intervals, $interval) {
    if (empty($intervals))
        return true;
    
    $size = sizeof($intervals) - 1;
    
    if ($interval[0] >= $intervals[$size][1] || $interval[1] <= $intervals[0][0])
        return true;
    
    for ($i = 0; $i < $size; $i++)
        if ($interval[0] >= $intervals[$i][1] && $interval[1] <= $intervals[$i + 1][0])
            return true;
        
    return false;
}

function getMinMax($business) {
    $min = false;
    $max = false;
    
    $shorts = [
        'sun_times',
        'mon_times',
        'tue_times',
        'wed_times',
        'thu_times',
        'fri_times',
        'sat_times'
    ];
    
    foreach ($shorts as $short) {
        $times = $business[$short];
        $size  = intval($times[0]);
        
        for ($i = 0; $i < $size; $i++) {
            $minTest = $times[$i * 2 + 1];
            $maxTest = $times[$i * 2 + 2];
            
            if ($min === false)
                $min = $minTest;
            if ($max === false)
                $max = $maxTest;
            
            if ($minTest < $min)
                $min = $minTest;
            
            if ($maxTest > $max)
                $max = $maxTest;
        }
    }
    
    if ($min === false || $max === false) {
        $min = '08:00';
        $max = '16:00';
    }
    
    return [$min, $max];
}

function gen_free($business, $timezone) {
    $shorts = [
        'sun_times',
        'mon_times',
        'tue_times',
        'wed_times',
        'thu_times',
        'fri_times',
        'sat_times'
    ];
    
    if (empty($timezone))
        $timezone = 'UTC';
    
    date_default_timezone_set($timezone);
    
    $offset = date('P');
    $free = [];

    for ($i = 0; $i <= 30; $i++) {
        
        $day_raw = strtotime('+' . $i . ' day');
        $date     = date('Y-m-d\T', $day_raw);
        
        $day_index = intval(date('w', $day_raw));
        $day_times = $business[$shorts[$day_index]];
        
        $day_size  = intval($day_times[0]);
        
        if ($day_size == 0)
            continue;
        
        $one_day = [];
        
        for ($x = 0; $x < $day_size; $x++) {
            $day_start = $date . $day_times[$x * 2 + 1] . $offset;
            $day_end   = $date . $day_times[$x * 2 + 2] . $offset;
            
            $one_day[] = [strtotime($day_start), strtotime($day_end)];
        }
        
        $one_day = merge_day($one_day);
        foreach ($one_day as $one_day_time)
            $free[] = $one_day_time;
    }
    
    return $free;
}

function merge_day($data) {
    usort($data, function($a, $b) {
        return $a[0] - $b[0];
    });

    $n = 0; $len = count($data);
    for ($i = 1; $i < $len; ++$i)
        if ($data[$i][0] > $data[$n][1] + 1)
            $n = $i;
        else {
            if ($data[$n][1] < $data[$i][1])
                $data[$n][1] = $data[$i][1];
            unset($data[$i]);
        }
    $data = array_values($data);
    
    return $data;
}

function get_intervals($timezone) {
    if (empty($timezone))
        $timezone = 'UTC';
    
    date_default_timezone_set($timezone);
    
    $offset = '00:00' . date('P');
    $intervals = [];
    
    for ($days = 0; $days <= 30; $days++) {
    
        $start_time = strtotime('+' . $days . ' day');
        $id    = date('[Y,n-1,j]', $start_time);
        $start = strtotime(date('Y-m-d\T', $start_time) . $offset);
        $end   = strtotime(date('Y-m-d\T', strtotime('+' . ($days + 1) . ' day')) . $offset);
        
        $intervals[$id] = [$start, $end];
    }
    
    return $intervals;
}

function cut_interval($interval, $tick) {
    $rtn = [];
    for ($i = $interval[1] - $tick; $i >= $interval[0]; $i -= $tick)
        $rtn[] = [$i, $i + $tick];
    return array_reverse($rtn);
}

function dateToVal($timezone, $date, $time) {
    if (empty($timezone))
        $timezone = 'UTC';
    
    date_default_timezone_set($timezone);
    
    return strtotime($date . 'T' . $time . date('P'));
}





