[![](https://poggit.pmmp.io/shield.state/BetterNPC)](https://poggit.pmmp.io/p/BetterNPC) [![](https://poggit.pmmp.io/shield.api/BetterNPC)](https://poggit.pmmp.io/p/BetterNPC)
# About
BetterNPC is a npc plugin for PocketMine-MP.

## Features
- Entities are being saved in the world directly
- **Editable** via command or by right-clicking the entity (Required Permission: betternpc.edit)
- Customize the entities to your will
- Assign hit actions to your entities

## Commands
| Name                              | Description       | Permission        |
|-----------------------------------|-------------------|-------------------|
| /betternpc                        | Main Command      | betternpc.command |
| /betternpc create                 | Create an entity  |                   |
| /betternpc edit [entityId: int]   | Edit an entity    |                   |
| /betternpc remove [entityId: int] | Remove an entity  |                   |
| /betternpc list                   | List all entities |                   |

## Current Hit Actions
| Action       | Description                      | Note                                       |
|--------------|----------------------------------|--------------------------------------------|
| Emote        | The entity is doing an emote     | The emote only works for human entities    |
| Run Command  | The player runs a command        |                                            |
| Send Message | Send a message to the player     |                                            |

## Action Tag-Replacements
Current player = The player who clicks on the NPC and runs the action

| Tag      | Description               |
|----------|---------------------------|
| {player} | Current player username   |

## Config
```yaml
# You can find all the emotes at https://github.com/JustTalDevelops/bedrock-emotes?tab=readme-ov-file#emotes
emotes:
  wave:
    id: 4c8ae710-df2e-47cd-814d-cc7bf21a3d67
    name: Wave
  yoga:
    id: 3f1bdf46-80b0-4a64-b631-4ac2f2491165
    name: Yoga

# The cooldown for each hit action for the entities in seconds (Default: 10)
hit-action-cooldown: 10

# The cooldown for the random emote setting for entities in seconds (Default: 60)
random-emote-cooldown: 60

# If true, when a player hits the entity and the entity has a "run_command" hit action, the server performs the command and not the client. (Default: false)
server-command-handling: false
```