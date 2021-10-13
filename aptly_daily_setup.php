#!/usr/bin/php
<?php
const UBUNTU_DISTROS = array("focal", "groovy", "hirsute", "impish");
const UBUNTU_COMPONENTS = array("main", "multiverse", "restricted", "universe");
const UBUNTU_PATCH_LEVELS = array("backports", "proposed", "release", "updates");
const UBUNTU_MIRRORS = array("security","ubuntu");
const DATES = array("today", "yesterday");
/*
$expanded_names = array();
foreach ($distros as &$distrosvalue) {
	for( $i = 0; $i < count($subdistro_names); ++$i) {
		foreach ($components as &$componentsvalue) {
			$expanded_names[] = sprintf("%s-%s-%s", $distrosvalue, $subdistro_names[$i], $componentsvalue);
		}
	}
}

var_dump($expanded_names);
*/

class OSMirror
{
	public $OS = "";
	public $Components = array();
	public $PatchLevels = array();
	public $Mirrors = array();

	/**
	 * The constructor
	 */
	public function __construct(string $os, array $comps, array $patchlevels, array $mirrors) {
		$this->OS = $os;
		$this->Components = $comps;
		$this->PatchLevels = $patchlevels;
		$this->Mirrors = $mirrors;
	}
}

/**
 * Build the array of ubuntu OSMirrors
 */
$ubuntu_os_mirrors = array();
foreach(UBUNTU_DISTROS as $distro) {
	var_dump($distro);
	$ubuntu_os_mirrors[] = new OSMirror($distro, UBUNTU_COMPONENTS, UBUNTU_PATCH_LEVELS, UBUNTU_MIRRORS);
}

var_dump($ubuntu_os_mirrors);

//exec("aptly snapshot list -raw", $all_snapshots );

/*
var_dump($all_snapshots);
foreach($all_snapshots as $snapshot) {
	var_dump(explode("-", $snapshot));
}
*/

$aptly_merge_commandline = "aptly snapshot merge -latest result sources"

?>
