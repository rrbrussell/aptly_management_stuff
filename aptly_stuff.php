#!/usr/bin/php
<?php
$distros = ["focal", "groovy", "hirsute", "impish", "jammy"];
$components = ["main", "multiverse", "restricted", "universe"];
$subdistro_names = ["backports", "proposed", "release", "security", "updates"];
//$subdistro_names = array("security");
$subdistro_url = ["backports", "proposed", "", "security", "updates"];
//$subdistro_url = array("security");
foreach ($distros as &$distrosvalue) {
  for ($i = 0; $i < count($subdistro_names); ++$i) {
    foreach ($components as &$componentsvalue) {
      $mirror_name = sprintf(
        "%s-%s-%s",
        $distrosvalue,
        $subdistro_names[$i],
        $componentsvalue
      );
      $url = "http://mirror.pit.teraswitch.com/ubuntu/";
      $snapshot_name = $mirror_name . "-today";
      $distro_comp = $distrosvalue;
      if (strlen($subdistro_url[$i]) != 0) {
        $distro_comp = $distro_comp . "-" . $subdistro_url[$i];
      }
      //printf("aptly mirror create ubuntu-%s %s %s %s\n", $mirror_name, $url, $distro_comp, $componentsvalue);
      //printf("aptly mirror edit -archive-url=%s ubuntu-%s\n", $url, $mirror_name);
      //printf("\n");
      //printf("aptly snapshot create security-%s from mirror security-%s\n",$snapshot_name, $mirror_name);
    }
  }
}

?>
