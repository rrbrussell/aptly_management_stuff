#!/usr/bin/php
<?php
$distros = array("focal", "groovy", "hirsute", "impish");
$components = array("main", "multiverse", "restricted", "universe");
$subdistro_names = array("backports", "proposed", "release", "updates");
//$subdistro_url = array("backports", "proposed", "", "updates");
foreach ($distros as &$distrosvalue) {
	for( $i = 0; $i < count($subdistro_names); ++$i) {
		foreach ($components as &$componentsvalue) {
			$mirror_name = sprintf("%s-%s-%s", $distrosvalue, $subdistro_names[$i], $componentsvalue);
			$snapshot_name = $mirror_name . "-today";
			printf("aptly snapshot create ubuntu-%s from mirror ubuntu-%s\n", $snapshot_name, $mirror_name);
			printf("aptly snapshot create security-%s from mirror security-%s\n",$snapshot_name, $mirror_name);
		}
	}
}
?>