# FlySystem
PocketMine-MP plugin that enables survival flight in specific worlds.

## Commands

| Command | Description          | Permission          |
| ------- | -------------------- | ------------------- |
| `/fly`  | Toggles flight mode. | `fly.command` |
| `/fly`  | Toggles flight mode. | `fly.bypass` |

## Permissions

| Permission          | Description                                 | Default |
| ------------------- | ------------------------------------------- | ------- |
| `flysystem.command` | Allows players to use the fly command.      | op      |
| `flysystem.bypass`  | Allows players to fly in restricted worlds. | op      |

## Configuration

```yaml
enabled-worlds:
  - world
  - survival
  - lobby

messages:
  fly-enabled: "Flight enabled."
  fly-disabled: "Flight disabled."
  world-disabled: "Flying is not allowed in this world."
```

## Installation

1. Download the latest release.
2. Place the plugin inside the `plugins/` directory.
3. Restart your PocketMine-MP server.
4. Edit the configuration if necessary.

## Requirements

* PocketMine-MP 5.x
* PHP 8.1 or higher

## License

This project is licensed under the MIT License.

## Author

Developed by Atlas Development.
