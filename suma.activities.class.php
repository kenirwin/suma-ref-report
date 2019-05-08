<?php

class Stats {
    public $dict = '';
    public $counts = array();
    public $activity_counts = array();
    public $activityTitles = array();
    public $activityGroupTitles = array();

    public function __construct($init, $suma_server_url, $startdate='', $enddate='')
    {
        $done = false;
        $i = 0;
        while (! $done) { 
        $suma_query_url = $suma_server_url . "/query/counts?";
        $suma_query_url .= 'id=' . $init . '&';

        if (isset($offset)) {
            $suma_query_url .= 'offset=' . $offset . '&';
        }

        if (isset($startdate)) { 
            $suma_query_url .= 'sdate='.$startdate.'&';
        }
        if (isset($startdate)) { 
            $suma_query_url .= 'edate='.$enddate.'&';
        }

        $json = file_get_contents($suma_query_url);
        $response = json_decode($json);    

        $this->dict = $response->initiative->dictionary;
        if (is_array($this->counts)) {
            $this->counts = array_merge($this->counts,$response->initiative->counts);
        }
        else { 
            $this->counts = $response->initiative->counts;
        }
        if ($i == 0) { //only on first iteration
            $this->firstTime = $response->initiative->counts[0]->time;
        }

        if ($response->status->{'has more'} == 'false') {
            $done = true;
        }
        $i++;
        if ($i > MAX_API_QUERIES) { $done = true; } //this is a failsafe -- if it ever exceeds x-many loops, stop
        if (! $done) { 
            $offset = $response->status->offset; 
            $status = $response->status->{'has more'};
        }

        
        $activityTitles = array();
        foreach ($this->dict->activities as $a) {
            if ($a->id > 0)
                $activityTitles[$a->id] = $a->title;
        }
        $this->activityTitles = $activityTitles;

        $activityGroupTitles = array();
        foreach ($this->dict->activityGroups as $a) {
            if ($a->id > 0)
                $activityGroupTitles[$a->id] = $a->title;
        }
        $this->activityGroupTitles = $activityGroupTitles;
        }
    }

    public function ActivityGroups() {
        $activityGroups = array();
        foreach ($this->dict->activityGroups as $a) {
            array_push($activityGroups, $a);
        }
        return $activityGroups;
    }
    public function Activities() {
        $activities = array();
        foreach ($this->dict->activities as $a) {
            array_push($activities, $a);
        }
        return $activities;
    }


    public function ListWeeks($start, $end) {
        $weeks = array();
        $starttime = strtotime($start);
        $endtime = strtotime($end);
        $oneday = 60*60*24; 
        for ($date = $starttime; $date <= $endtime; $date+=$oneday) {
            if (date("l",$date) == "Sunday") {
                $this_saturday = date("Y-m-d", $date+($oneday*6));
                $this_sunday = date("Y-m-d",$date);
                $next_sunday = date("Y-m-d",$date+($oneday*7));
                $week = array("start"=>$this_sunday,
                               "end"  =>$next_sunday,
                               "start_label" => $this_sunday,
                               "end_label" => $this_saturday
                               );
                    array_push($weeks, $week);
            }
        }
        return ($weeks);
    }
    
    public function Sum($filters=array()) {
        $sum = 0;
        foreach ($this->counts as $obj) {
            $include = true;
            if (sizeof($filters) > 1) {
                foreach ($filters as $k=>$v) {
                    if ($k == "start") {
                        if (strtotime($obj->time) < strtotime($v)) {
                            $include = false;
                        }
                    }
                    elseif ($k == "end") {
                        if (strtotime($obj->time) > strtotime($v)) {
                            $include = false;
                        }
                    }
                    /*
                      there could also be an activities filter in which an array of activities is compared against the $obj->activities array for a match, but I'll have to think the through more.
                    */
                }

            }
            if ($include === true) {
                $sum += $obj->number; 
            }
        }
        return $sum;
    }
    public function ActivitiesByGroup($id='') {
        $abg = array();
        foreach ($this->dict->activities as $a) {
            
            $ag = $a->activityGroup;
            if ($id == '') {
                $abg[$ag][] = $a;
            }
            elseif ($ag == $id) {
                $abg[] = $a;
            }
        }
        return $abg;
    }

    public function ListCrosstabPairs() {
        $crosstab_pairs = array();
        foreach ($this->activityGroupTitles as $ag_id=>$array) {
            foreach ($this->activityGroupTitles as $ag2_id => $array2) {
                if ($ag_id != $ag2_id) {
                    $crosstab_pairs[] = array($ag_id, $ag2_id);
                } 
            }
        }
        return $crosstab_pairs;
    }

    public function Crosstab ($a, $b) { //$a,$b are activityGroups
        
        $array_a = array();
        $array_b = array();
        foreach ($this->ActivitiesByGroup($a) as $obj) {
            array_push($array_a, $obj->id);
        }

        foreach ($this->ActivitiesByGroup($b) as $obj) {
            array_push($array_b, $obj->id);
        }
        
        foreach ($this->counts as $obj) {
            $a_items = array();
            $b_items = array();
            foreach ($obj->activities as $id) {
                if (in_array($id, $array_a)) { 
                    array_push($a_items, $id);
                }

                elseif (in_array($id, $array_b)) {
                    array_push($b_items, $id);
                }
            }
            if (!empty($a_items) && !empty($b_items)) {
                foreach ($a_items as $ai) {
                    foreach ($b_items as $bi) {
                        $crosstabs[$ai][$bi] += $obj->number;
                    }
                }
                    
            }
        }
        return $crosstabs;
    }

    public function CrosstabGroupMatrix($a,$b) {
        $array_a = array();
        $array_b = array();
        foreach ($this->ActivitiesByGroup($a) as $obj) {
            array_push($array_a, $obj->id);
        }

        foreach ($this->ActivitiesByGroup($b) as $obj) {
            array_push($array_b, $obj->id);
        }
        return array($array_a, $array_b); 
    }

    public function GetStatsByActivity() {
        foreach ($this->counts as $count_obj) {
            foreach ($count_obj->activities as $activity_id) {
                if (isset($this->activity_counts[$activity_id])) { 
                    $this->activity_counts[$activity_id] += $count_obj->number;
                }
                else {
                    $this->activity_counts[$activity_id] = $count_obj->number;
                }
            }
        }
    } 
    public function GetGroupStats () {
        $return = array();
        $groups = $this->ActivitiesByGroup();
        $this->GetStatsByActivity();
        foreach ($this->dict->activityGroups as $ag) {
            //            print '<h4>'.$ag->title. ' ('. $ag->id .')</h4>'.PHP_EOL;
            //            print_r ($groups[$ag->id]);
            $ag_label = $ag->title;
            $ag_stats = array();
            foreach ($groups[$ag->id] as $activity_object) {
                $activity_id = $activity_object->id;
                $ag_stats[$activity_object->title] = $this->activity_counts[$activity_id];
                    //                print '<li>' . $activity_object->title . ' : '. $this->activity_counts[$activity_id] . '</li>'.PHP_EOL;
            }
            $ag_array = array ($ag_label => $ag_stats);
            $return[] = $ag_array;
        }
        return $return;
    } //end function GetGroupStats
}//end class

?>