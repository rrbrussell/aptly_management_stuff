#!/usr/bin/php
<?php
/**
 * These constants define which snapshots are managed by this
 * tool
 */
const UBUNTU_DISTROS = ["focal", "groovy", "hirsute", "impish"];
const UBUNTU_COMPONENTS = ["main", "multiverse", "restricted", "universe"];
const UBUNTU_PATCH_LEVELS = [
  "backports",
  "proposed",
  "release",
  "updates",
  "security",
];
const UBUNTU_MIRRORS = ["ubuntu"];
const DATES = ["today", "yesterday"];
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

class OSMirror {
  private const MIRROR_STRING_TEMPLATE = "%s-%s-%s-%s";
  private const SNAPSHOT_STRING_TEMPLATE =
    OSMirror::MIRROR_STRING_TEMPLATE . "-%s";
  public $OS = "";
  public $Components = [];
  public $PatchLevels = [];
  public $Mirrors = [];

  /**
   * The constructor
   */
  public function __construct(
    string $os,
    array $comps,
    array $patchlevels,
    array $mirrors
  ) {
    $this->OS = $os;
    $this->Components = $comps;
    $this->PatchLevels = $patchlevels;
    $this->Mirrors = $mirrors;
  }

  public function GetSnapshotsForDate(string $date) {
    $expanded = [];
    foreach ($this->Mirrors as $mirror) {
      foreach ($this->PatchLevels as $patchlevel) {
        foreach ($this->Components as $component) {
          $expanded[] = sprintf(
            OSMirror::SNAPSHOT_STRING_TEMPLATE,
            $mirror,
            $this->OS,
            $patchlevel,
            $component,
            $date
          );
        }
      }
    }
    unset($mirror, $patchlevel, $component);
    return $expanded;
  }

  public function ConstructArrayOfMirrors() {
    $expanded = [];
    foreach ($this->Mirrors as $mirror) {
      foreach ($this->PatchLevels as $patchlevel) {
        foreach ($this->Components as $component) {
          $expanded[] = sprintf(
            OSMirror::MIRROR_STRING_TEMPLATE,
            $mirror,
            $this->OS,
            $patchlevel,
            $component
          );
        }
      }
    }
    unset($mirror, $patchlevel, $component);
    return $expanded;
  }

  public function GetMirrorsForComponent(string $component) {
    $expanded = [];
    foreach ($this->Mirrors as $mirror) {
      foreach ($this->PatchLevels as $patchlevel) {
        $expanded[] = sprintf(
          OSMirror::MIRROR_STRING_TEMPLATE,
          $mirror,
          $this->OS,
          $patchlevel,
          $component
        );
      }
    }
    unset($mirror, $patchlevel);
    return $expanded;
  }

  public function GetSnapshotsForComponent(string $component, string $date) {
    $expanded = [];
    foreach ($this->Mirrors as $mirror) {
      foreach ($this->PatchLevels as $patchlevel) {
        $expanded[] = sprintf(
          OSMirror::SNAPSHOT_STRING_TEMPLATE,
          $mirror,
          $this->OS,
          $patchlevel,
          $component,
          $date
        );
      }
    }
    return $expanded;
  }

  public function GetMergedSnapshotForComponent(
    string $component,
    string $date
  ) {
    return sprintf(
      OSMirror::SNAPSHOT_STRING_TEMPLATE,
      "merged",
      $this->OS,
      "complete",
      $component,
      $date
    );
  }

  public function GetMergedSnapshotsForDate(string $date) {
    $expanded = [];
    foreach ($this->Components as $component) {
      $expanded[] = $this->GetMergedSnapshotForComponent($component, $date);
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
foreach(UBUNTU_DISTROS as $distro) {
	$current = new OSMirror($distro, UBUNTU_COMPONENTS,
	UBUNTU_PATCH_LEVELS, UBUNTU_MIRRORS);
	foreach(UBUNTU_COMPONENTS as $component) {
		$aptly_merge_commandline =
		"snapshot rename %s %s\n";
		printf($aptly_merge_commandline,
			$current->GetMergedSnapshotForComponent(
				$component, "today"),
			$current->GetMergedSnapshotForComponent(
				$component, "yesterday")
			);
	}
}*/
/*
foreach(UBUNTU_DISTROS as $distro) {
	$current = new OSMirror($distro, UBUNTU_COMPONENTS,
	UBUNTU_PATCH_LEVELS, UBUNTU_MIRRORS);
	foreach(UBUNTU_COMPONENTS as $component) {
		$aptly_merge_commandline =
		"snapshot merge -latest " .
		$current->GetMergedSnapshotForComponent(
			$component, "today") . " " .
		implode(" ", $current->GetSnapshotsForComponent(
			$component, "today"));
		print($aptly_merge_commandline . "\n");
	}
}
*/
/*
foreach(UBUNTU_DISTROS as $distro) {
	$current = new OSMirror($distro, UBUNTU_COMPONENTS,
	UBUNTU_PATCH_LEVELS, UBUNTU_MIRRORS);
	$aptly_publish_commandline =
		"publish switch -component=" .
		$current->GetComponentList(",") . " " .
		$distro . " ubuntu " .
		implode(" ",
		$current->GetMergedSnapshotsForDate("today"));
	print($aptly_publish_commandline . "\n");
}
*/
/*
$output = array();
exec("aptly mirror list -raw", $output);
foreach ($output as &$line) {
	printf("snapshot create %s-today from mirror %s\n", $line, $line);
}
*/


?>
