# BetterNPC
BetterNPC is a npc plugin for PocketMine-MP.

## Features
- Entities are being saved in the world directly
- **Editable** via command or by right-clicking the entity
- Customize the entities to your will
- Assign hit actions to your entities

## Commands
| Name                        | Description       | Permission        |
|-----------------------------|-------------------|-------------------|
| /betternpc                  | Main Command      | betternpc.command |
| /betternpc create           | Create an entity  |                   |
| /betternpc edit             | Edit an entity    |                   |
| /betternpc remove [id: int] | Remove an entity  |                   |
| /betternpc list             | List all entities |                   |

## Current Actions
| Action       | Description                      | Note                                       |
|--------------|----------------------------------|--------------------------------------------|
| Emote        | The entity is doing an emote     | The emote only works for human entities    |
| Animation    | The entity is doing an animation | This is only possible with a resource pack |
| Run Command  | The player runs a command        |                                            |
| Send Message | Send a message to the player     |                                            |
