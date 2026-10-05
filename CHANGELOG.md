# Changelog

## [1.1.0] - Unreleased

### Added

- `Activity::$isManual`, `Activity::$isFlagged` and `Activity::$isTrainer`, mapped from the `manual`, `flagged` and `trainer` payload keys, `null` when the key is absent.
- `Activity::$uploadId`, mapped from `upload_id` and falling back to `upload_id_str`, `null` when both are absent or when the value is not a positive 64-bit integer.
- `Activity::$externalId` and `Activity::$deviceName`, mapped from the `external_id` and `device_name` payload keys, `null` when the key is absent.

The six new constructor parameters are optional and come last, so existing positional calls keep working.

## [1.0.0]

Initial release.
