/*
 * With the new NMR system the overall phase count was replaced by a yearly phase count
 * for RR calculations. However, vdip does also use the overall phase count as measure
 * e.g. for joining games. The overall phase count is readded with vdip version 66.
 * This script does add the current values of yearly phase count to the overall 
 * phase count to compensate the last months without phase count increment
 * since the NMR update.
 */

UPDATE wD_Users SET phaseCount = phaseCount + yearlyPhaseCount;

UPDATE `wD_vDipMisc` SET `value` = '66' WHERE `name` = 'Version'
