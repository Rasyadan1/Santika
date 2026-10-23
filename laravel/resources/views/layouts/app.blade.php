<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SANTIKA — Sewa Alat Santet & Pelet Seketika</title>

    <!-- Google Fonts (Creepster & Space Grotesk) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Creepster&family=Space+Grotesk:wght@400;500;600;700&display=swap">

    <!-- Tailwind CSS v4 Browser Engine Script -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Custom Style Engine dari Lovable (Gradient, OKLCH Colors, Glows, & Animations) -->
    <style type="text/tailwindcss">
        @custom-variant dark (&:is(.dark *));

        @theme inline {
            --radius-sm: calc(var(--radius) - 4px);
            --radius-md: calc(var(--radius) - 2px);
            --radius-lg: var(--radius);
            --radius-xl: calc(var(--radius) + 4px);
            --color-background: var(--background);
            --color-foreground: var(--foreground);
            --color-card: var(--card);
            --color-card-foreground: var(--card-foreground);
            --color-popover: var(--popover);
            --color-popover-foreground: var(--popover-foreground);
            --color-primary: var(--primary);
            --color-primary-foreground: var(--primary-foreground);
            --color-secondary: var(--secondary);
            --color-secondary-foreground: var(--secondary-foreground);
            --color-muted: var(--muted);
            --color-muted-foreground: var(--muted-foreground);
            --color-accent: var(--accent);
            --color-accent-foreground: var(--accent-foreground);
            --color-destructive: var(--destructive);
            --color-destructive-foreground: var(--destructive-foreground);
            --color-border: var(--border);
            --color-input: var(--input);
            --color-ring: var(--ring);
            --color-sidebar: var(--sidebar);
            --color-sidebar-foreground: var(--sidebar-foreground);
            --color-sidebar-primary: var(--sidebar-primary);
            --color-sidebar-accent: var(--sidebar-accent);
            --color-sidebar-border: var(--sidebar-border);
            --color-glass: var(--glass);
            --color-success: var(--success);
            --font-display: "Creepster", cursive;
            --font-sans: "Space Grotesk", system-ui, sans-serif;
        }

        :root {
            --radius: 0.75rem;
            --background: oklch(0.14 0.01 300);
            --foreground: oklch(0.95 0.01 300);
            --card: oklch(0.19 0.015 300);
            --card-foreground: oklch(0.95 0.01 300);
            --popover: oklch(0.19 0.015 300);
            --popover-foreground: oklch(0.95 0.01 300);
            --primary: oklch(0.496 0.233 303.9); /* Deep Purple #7e22ce */
            --primary-foreground: oklch(0.98 0.01 300);
            --secondary: oklch(0.24 0.02 300);
            --secondary-foreground: oklch(0.95 0.01 300);
            --muted: oklch(0.22 0.015 300);
            --muted-foreground: oklch(0.68 0.03 300);
            --accent: oklch(0.3 0.08 303);
            --accent-foreground: oklch(0.95 0.01 300);
            --destructive: oklch(0.514 0.199 16.9); /* Crimson #be123c */
            --destructive-foreground: oklch(0.98 0.01 300);
            --border: oklch(1 0 0 / 10%);
            --input: oklch(1 0 0 / 12%);
            --ring: oklch(0.496 0.233 303.9);
            --sidebar: oklch(0.17 0.015 300);
            --sidebar-foreground: oklch(0.95 0.01 300);
            --sidebar-primary: oklch(0.496 0.233 303.9);
            --sidebar-accent: oklch(0.26 0.05 303);
            --sidebar-border: oklch(1 0 0 / 8%);
            --glass: oklch(0.2 0.02 300 / 45%);
            --success: oklch(0.72 0.17 160);
        }

        @utility bg-mystic {
            background-color: var(--background);
            background-image:
                radial-gradient(ellipse 60% 50% at 15% 0%, color-mix(in oklab, var(--primary) 35%, transparent), transparent),
                radial-gradient(ellipse 50% 40% at 90% 100%, color-mix(in oklab, var(--destructive) 25%, transparent), transparent);
            background-attachment: fixed;
        }

        @utility glow-primary {
            box-shadow: 0 0 30px -5px color-mix(in oklab, var(--primary) 60%, transparent);
        }

        @utility animate-float {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-4deg); }
            50% { transform: translateY(-14px) rotate(4deg); }
        }

        @layer base {
            * {
                border-color: var(--color-border);
            }
            body {
                background-color: var(--color-background);
                color: var(--color-foreground);
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-mystic text-foreground min-h-screen">

    @yield('content')

</body>
</html>