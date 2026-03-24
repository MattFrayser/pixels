---
name: Project Architecture
description: Core architecture decisions for the pixels collaborative pixel art app
type: project
---

## App Overview
Collaborative pixel art editor. Projects contain canvases (frames) which contain pixels.

## Editor
- Single universal editor component for all users
- Guest state lives in localStorage
- Authed users can save to DB, reopen saved projects, create/join rooms
- `project` prop = null means ephemeral mode, project object means full features
- Homepage (`/`) is the ephemeral editor, not a listing page

## Projects & Canvases
- Canvases are animation frames within a project
- Project has width, height, framerate
- Canvas has sort_order, pixels, snapshot
- Editor loads all frames client-side, switching is client-side only
- Save flushes entire project + all canvas/pixel state to server at once
- No per-canvas routes — canvases managed within editor

## Snapshots
- Canvas.snapshot stores a URL (disk to start, S3 later)
- Generated server-side as a queued job
- Single canvas → PNG, multiple canvases → GIF (animation plays on listing)
- Triggered on: room leave, manual save
- Client does NOT generate snapshots (server reads pixel rows)

## Pixel History
- pixel_events table (append-only log): canvas_id, x, y, color_from, color_to, placed_by (user FK), created_at
- Pixel rows = current state (source of truth)
- Events = history log for undo/redo
- Undo/redo scope TBD (per-user vs per-canvas)

## Rooms
- One room per project
- Room.public flag for discovery
- Members via pivot with role column
- WebSocket channel: rooms.{room} — pixel placement, frame changes, presence
- No mid-session DB persistence — clients hold state, save = commit
- Frame management (add/remove/reorder) also goes over WebSocket

## Public Projects
- Projects have a public flag
- Anyone can browse /projects and open a public project
- Opening loads pixel data into editor locally (no DB write)
- Saving a "forked" public project just creates a new project via POST /projects

## User Feature Matrix
| Feature | Guest | Authed |
|---|---|---|
| Edit | ✓ | ✓ |
| Export | ✓ | ✓ |
| Browse public projects | ✓ | ✓ |
| Open public project | ✓ (local) | ✓ (local) |
| Save | ✗ | ✓ |
| Rooms | ✗ | ✓ |
