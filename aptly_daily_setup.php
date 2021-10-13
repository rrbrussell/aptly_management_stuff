#!/usr/bin/php
<?php
/**
 * These constants define which snapshots are managed by this
 * tool
 */
const UBUNTU_DISTROS = array("focal", "groovy", "hirsute",
	"impish");
const UBUNTU_COMPONENTS = array("main", "multiverse",
	"restricted", "universe");
const UBUNTU_PATCH_LEVELS = array("backports", "proposed",
	"release", "updates");
const UBUNTU_MIRRORS = array("security","ubuntu");
const DATES = array("today", "yesterday");
/*
$expanded_names = array();
foreach ($distros as &$distrosvalue) {
	for( $i = 0; $i < count($subdistro_names); ++$i) {
		foreach ($components as &$componentsvalue) {
			$expanded_names[] = sprintf("%s-%s-%s",
			$distrosvalue, $subdistro_names[$i],
			$componentsvalue);
		}
	}
}

var_dump($expanded_names);
*/

class OSMirror
{
	private const MIRROR_STRING_TEMPLATE = "%s-%s-%s-%s";
	private const SNAPSHOT_STRING_TEMPLATE =
		OSMirror::MIRROR_STRING_TEMPLATE . "-%s";
	public $OS = "";
	public $Components = array();
	public $PatchLevels = array();
	public $Mirrors = array();

	/**
	 * The constructor
	 */
	public function __construct(string $os, array $comps, array
		$patchlevels, array $mirrors) {
		$this->OS = $os;
		$this->Components = $comps;
		$this->PatchLevels = $patchlevels;
		$this->Mirrors = $mirrors;
	}

	public function GetSnapshotsForDate( string $date) {
		$expanded = array();
		foreach ($this->Mirrors as $mirror) {
			foreach ($this->PatchLevels as $patchlevel) {
				foreach ($this->Components as $component) {
					$expanded[] = 
					sprintf(OSMirror::SNAPSHOT_STRING_TEMPLATE,
						$mirror, $this->OS, $patchlevel,
						$component, $date);
				}
			}	
		}
		unset($mirror, $patchlevel, $component);
		return $expanded;
	}

	public function ConstructArrayOfMirrors() {
		$expanded = array();
		foreach ($this->Mirrors as $mirror) {
			foreach ($this->PatchLevels as $patchlevel) {
				foreach ($this->Components as $component) {
					$expanded[] = 
					sprintf(OSMirror::MIRROR_STRING_TEMPLATE,
						$mirror, $this->OS, $patchlevel,
						$component);
				}
			}	
		}
		unset($mirror, $patchlevel, $component);
		return $expanded;
	}

	public function GetMirrorsForComponent(string $component) {
		$expanded = array();
		foreach ($this->Mirrors as $mirror) {
			foreach ($this->PatchLevels as $patchlevel) {
				$expanded[] = 
				sprintf(OSMirror::MIRROR_STRING_TEMPLATE,
					$mirror, $this->OS, $patchlevel, $component);
			}
		}
		unset($mirror, $patchlevel);
		return $expanded;
	}

	public function GetSnapshotsForComponent(string $component,
		string $date) {
		$expanded = array();
		foreach ($this->Mirrors as $mirror) {
			foreach ($this->PatchLevels as $patchlevel) {
				$expanded[] =
				sprintf(OSMirror::SNAPSHOT_STRING_TEMPLATE,
					$mirror, $this->OS, $patchlevel, $component,
					$date);
			}
		}
		return $expanded;
	}

	public function GetMergedSnapshotForComponent(
		string $component, string $date) {
		return sprintf(OSMirror::SNAPSHOT_STRING_TEMPLATE,
			"merged", $this->OS, "complete", $component, $date);
	}

	public function GetMergedSnapshotsForDate(string $date) {
		$expanded = array();
		foreach ($this->Components as $component) {
			$expanded[] = $this->GetMergedSnapshotForComponent(
				$component, $date);
		}
		return $expanded;
	}

	public function GetComponentList(string $sep) {
		return implode($sep, $this->Components);
	}
}

/**
 * Build the array of ubuntu OSMirrors
 */
/*
foreach(array("groovy", "hirsute", "impish") as $distro) {
	$current = new OSMirror($distro, UBUNTU_COMPONENTS,
	UBUNTU_PATCH_LEVELS, UBUNTU_MIRRORS);
	foreach(UBUNTU_COMPONENTS as $component) {
		$aptly_merge_commandline =
		"aptly snapshot merge -latest " .
		$current->GetMergedSnapshotForComponent(
			$component, "today") . " " .
		implode(" ", $current->GetSnapshotsForComponent(
			$component, "today"));
		print($aptly_merge_commandline . "\n");
	}
}*/

foreach(array("groovy", "hirsute", "impish") as $distro) {
	$current = new OSMirror($distro, UBUNTU_COMPONENTS,
	UBUNTU_PATCH_LEVELS, UBUNTU_MIRRORS);
	$aptly_publish_commandline =
		"aptly publish snapshot -acquire-by-hash -distribution="
		. $distro . "-complete -component=" .
		$current->GetComponentList(",") . " " .
		implode(" ",
		$current->GetMergedSnapshotsForDate("today")) .
		" ubuntu";
	print($aptly_publish_commandline . "\n");
}

?>