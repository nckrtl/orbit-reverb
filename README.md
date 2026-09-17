# Orbit Reverb

A plain Laravel [Reverb](https://reverb.laravel.com) server. It has no
business logic of its own: it accepts WebSocket connections over the Pusher
protocol and lets one broadcaster (the Orbit Gateway) publish events to one
private channel. Orbit deploys and runs this app the same way it deploys any
other app; nothing in this repository is Orbit-specific.

## Configuration

One Reverb app, configured entirely from the environment. See
`.env.example` for the full list; the values that matter are:

| Variable | Meaning |
| --- | --- |
| `REVERB_APP_ID` | Reverb application ID. |
| `REVERB_APP_KEY` | Public key clients (including the Gateway) connect with. |
| `REVERB_APP_SECRET` | Secret the Gateway signs broadcast requests with. |
| `REVERB_HOST` | Public hostname clients connect to: `reverb.orbit`. |
| `REVERB_PORT` | Public port: `443`. |
| `REVERB_SCHEME` | Public scheme: `https` (so clients use `wss://`). |
| `REVERB_SERVER_HOST` | Local bind address for the server process: `0.0.0.0`. |
| `REVERB_SERVER_PORT` | Local bind port for the server process: `8080`. |

Channel authorization happens on the Orbit Gateway
(`/broadcasting/auth`), not here. `allowed_origins` is `*` because origins
don't gate anything on this app; the Gateway is the only thing that decides
who may subscribe to a channel.

Session, cache, and queue all use `file`/`sync` drivers so the app boots and
runs without a database. `/up` is Laravel's stock health check.

## Running it

Orbit runs this process on the gateway node:

```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

Caddy in front of it (managed by Orbit) terminates TLS and handles the
WebSocket upgrade for `reverb.orbit`, then forwards the connection to that
port.
