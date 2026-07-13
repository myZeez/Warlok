---
name: Warlok
description: Hyperlocal UMKM discovery — find the warung next door, chat on WhatsApp.
colors:
  brand-50: "oklch(0.97 0.02 55)"
  brand-100: "oklch(0.94 0.045 54)"
  brand-200: "oklch(0.88 0.08 51)"
  brand-300: "oklch(0.8 0.12 47)"
  brand-400: "oklch(0.74 0.16 44)"
  brand-500: "oklch(0.68 0.19 41)"
  brand-600: "oklch(0.58 0.19 37)"
  brand-700: "oklch(0.52 0.17 34)"
  brand-800: "oklch(0.42 0.14 32)"
  brand-900: "oklch(0.34 0.11 30)"
  whatsapp: "#25d366"
  whatsapp-dark: "#128c7e"
  paper: "oklch(0.99 0.002 55)"
  neutral-50: "oklch(0.985 0.003 55)"
  neutral-200: "oklch(0.928 0.006 55)"
  neutral-400: "oklch(0.704 0.011 55)"
  neutral-500: "oklch(0.552 0.013 55)"
  neutral-700: "oklch(0.355 0.015 55)"
  neutral-900: "oklch(0.196 0.011 55)"
typography:
  display:
    fontFamily: "Plus Jakarta Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(2.25rem, 6vw, 3.75rem)"
    fontWeight: 800
    lineHeight: 1.02
    letterSpacing: "-0.03em"
  heading:
    fontFamily: "Plus Jakarta Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 800
    lineHeight: 1.2
    letterSpacing: "-0.01em"
  title:
    fontFamily: "Plus Jakarta Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 700
    lineHeight: 1.3
  body:
    fontFamily: "Plus Jakarta Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 500
    lineHeight: 1.6
  label:
    fontFamily: "Plus Jakarta Sans, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 600
    lineHeight: 1.2
rounded:
  sm: "0.75rem"
  md: "1rem"
  lg: "1.5rem"
  xl: "2rem"
  pill: "9999px"
spacing:
  xs: "0.5rem"
  sm: "0.75rem"
  md: "1rem"
  lg: "1.5rem"
  xl: "2rem"
  2xl: "3rem"
components:
  button-whatsapp:
    backgroundColor: "{colors.whatsapp}"
    textColor: "{colors.neutral-900}"
    rounded: "{rounded.pill}"
    padding: "12px 20px"
  button-whatsapp-hover:
    backgroundColor: "{colors.whatsapp}"
    filter: "brightness(0.95)"
  button-primary:
    backgroundColor: "{colors.brand-600}"
    textColor: "#ffffff"
    rounded: "{rounded.pill}"
    padding: "12px 24px"
  button-primary-hover:
    backgroundColor: "{colors.brand-700}"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "{colors.neutral-900}"
    border: "1px solid {colors.neutral-200}"
    rounded: "{rounded.pill}"
  card-surface:
    backgroundColor: "#ffffff"
    rounded: "{rounded.md}"
    shadow: "shadow-soft"
    padding: "16px"
  chip-category:
    backgroundColor: "#ffffff"
    rounded: "{rounded.md}"
    padding: "12px 16px"
---

# Design System: Warlok

## 1. Overview

**Creative North Star: "Clean Counter"**

Warlok pivoted from its original "Warm Warung" identity (saturated green, marketplace-familiar chrome) to a quieter, more confident register: a clean, editorial-Swiss product surface — generous white space, high-contrast near-black type, and exactly one saturated accent (a warm ember) used deliberately rather than everywhere. The function hasn't changed — this is still a hyperlocal WhatsApp-first directory of neighborhood UMKM — but the surface now reads like a well-made modern product rather than a marketplace-app clone.

This is a deliberate identity change, not a restyle of the same brand. The old system was "Committed" (green carrying 30-60% of every surface); this system is **Restrained**: near-white paper, near-black ink, and the brand ember accent appears only where it's doing real work — the logo mark, primary buttons, active nav states, one highlighted hero word. Everything else is neutral. Depth now comes from a soft ambient shadow on cards at rest (not flat-until-hover) — a deliberately more "product," less "flat commerce grid" feel.

**Key Characteristics:**
- Pill-shaped buttons and inputs everywhere; no sharp rectangular CTAs (unchanged from v1 — this still works).
- WhatsApp green (#25D366) is reserved exclusively for the "Pesan via WhatsApp" action. It is now the *only* green anywhere in the system — Warlok's own brand identity no longer uses green at all, which fully resolves the old two-greens tension by construction.
- Soft, ambient shadow on cards at rest (`shadow-soft`), lifting further on hover/interaction — not flat until touched.
- Mobile-first with a persistent bottom tab bar — this stays, because it's a real usability decision for a one-handed, phone-first audience, not a marketplace-mimicry decision.

## 2. Colors

**Strategy: Restrained neutrals + one confident accent.** Paper and ink carry the vast majority of every screen; the brand ember accent is spent on logo, primary CTAs, active states, and links only.

### Primary — Brand Ember
- **Brand 600** (`oklch(0.6 0.19 37)`): primary buttons ("Kirim Pendaftaran", "Lihat Toko"), logo mark, active bottom-nav icon + label, links, "Buka" status badges.
- **Brand 700** (`oklch(0.52 0.17 34)`): hover state for primary buttons, brand wordmark.
- **Brand 50/100**: icon-badge fills, subtle highlight backgrounds (category icon circles, "Buka" badge background).

### Functional — WhatsApp
- **WhatsApp Green** (`#25d366`): exclusively the "Pesan via WhatsApp" button background. Never reused elsewhere, including status badges — this is what keeps it instantly recognizable as *the* action, and it's the one place green appears in the entire system.
- **Text on the WhatsApp button is dark ink (`neutral-900`), not white.** White text on `#25d366` measures ~2:1 contrast — a real accessibility failure, not a style choice. Dark ink on the same green measures ~9:1. Hover feedback is a `brightness(0.95)` filter on the same green, not a background-color swap, so the fix holds in every state.

### Neutral (warm-tinted, tied to the brand's own hue — not a generic cool gray or cream default)
- **Paper** (`oklch(0.99 0.002 55)`): page background. Deliberately near-white, not cream/sand.
- **Neutral-50/100**: card fill on paper, icon-badge neutral fallback.
- **Neutral-200**: hairline borders, dividers.
- **Neutral-500**: secondary/muted text (shop names, timestamps) — verified ≥4.5:1 on white.
- **Neutral-900**: primary body text and headings (near-black, not pure `#000`).

### Status (semantic, deliberately not brand-colored)
- **Buka (open)**: `brand-100` / `brand-700` — uses the brand accent, since "active/available" is a legitimate accent use.
- **Tutup (closed)**: `neutral-100` / `neutral-500` — quiet, not alarming.
- **Errors**: standard `rose-600` — a system color, not a brand color.

### Named Rules
**The One-Green Rule.** WhatsApp green appears on exactly one element type across the entire product: the "Pesan via WhatsApp" button. It is never used for status, brand, or decoration. If something needs to signal "active" or "open," reach for the brand ember, never green.

## 3. Typography

**Body Font:** Plus Jakarta Sans (unchanged from v1 — it already suits this register; a geometric sans with enough warmth to avoid feeling cold, enough precision to read as "designed").

**Character:** Confidence through scale and weight, not decoration. The hero display size grew substantially (`clamp(2.25rem, 6vw, 3.75rem)` vs. v1's more conservative `clamp(1.5rem, 5vw, 1.875rem)`) with tighter tracking (`-0.03em`) — this is the single biggest visual signal of the pivot from "marketplace app" to "modern product."

### Hierarchy
- **Display** (800, `clamp(2.25rem, 6vw, 3.75rem)`, 1.02 line-height, -0.03em tracking, `text-wrap: balance`): homepage hero headline only.
- **Headline** (800, 1.25rem, -0.01em): page titles (UMKM name, "Daftarkan UMKM Kamu").
- **Title** (700, 1.125rem): section headings ("UMKM Pilihan", "Kenapa Warlok").
- **Body** (500, 0.9375rem, 1.6 line-height): descriptions, addresses, form labels.
- **Label** (600, 0.75rem): badges, chip captions.

### Named Rules
**The One-Family Rule** (unchanged): every role is a weight of Plus Jakarta Sans. No second family.
**The Confidence Rule** (new): display headings use the full available clamp range and tight tracking — a shy, small hero headline is the #1 tell of the old marketplace-app register this system left behind.

## 4. Elevation

Cards now carry a soft ambient shadow at rest (`shadow-soft`: a low-opacity double-layer shadow, not a hard drop shadow) — this replaces v1's "flat until touched" rule. The floating, slightly-elevated card is a deliberate signature of the new register (matches how modern product/SaaS surfaces present content as distinct, liftable objects rather than a flat commerce grid).

### Shadow Vocabulary
- **Rest** (`shadow-soft`): product cards, UMKM cards, testimonial-replacement feature cards, stat tiles.
- **Hover/interactive** (`shadow-soft-hover`, paired with `-translate-y-0.5`): any clickable card.
- **Prominent** (`shadow-soft-lg`): hero visual mockup, modals.
- **Admin (Filament)**: native Filament shadow, unchanged, out of scope.

### Named Rules
**The Floating-Object Rule.** Every card is a distinct, gently-elevated object at rest, not a flat bordered rectangle. Hover deepens the shadow and lifts slightly — depth is present by default now, motion just adds to it (a change from v1, where depth was hover-only).

## 5. Components

### Buttons
- **Shape:** Fully rounded/pill. Unchanged, still correct for this register.
- **Primary (WhatsApp):** `bg-whatsapp`, white text, bold, min-height 44px, `hover:bg-whatsapp-dark`, chat-bubble icon.
- **Primary (Brand):** `bg-brand-600`, white text, `hover:bg-brand-700`.
- **Ghost/Secondary:** white background, `border-neutral-200`, `text-neutral-900` — used where a screen needs a CTA that isn't the single most-important action.
- **Text link:** `text-brand-700`, semibold, no background.

### Chips (category chips, filters)
- White background, `rounded-2xl`, `shadow-soft` at rest (no border needed — the shadow does the separation work), icon in a `brand-50`-filled circle.
- `hover:-translate-y-0.5 hover:shadow-soft-hover`.

### Cards / Containers
- **Corner style:** `rounded-2xl` (product/UMKM cards, chips), `rounded-3xl` (section containers, hero visual, CTA banner).
- **Background:** white on the `paper` page background.
- **Shadow:** `shadow-soft` at rest, `shadow-soft-hover` + `-translate-y-0.5` on hover.
- **Border:** none by default (shadow replaces border as the separation cue); a hairline `neutral-200` border is acceptable only on inputs and dense data surfaces (admin tables), not on content cards.

### Inputs / Fields
- `rounded-full` for search/select, `rounded-2xl` for textareas/file inputs.
- `border-neutral-200`, white fill.
- Focus: `focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25`.

### Navigation
- **Header:** sticky, white/95%+blur, logo mark + region-select + search. `border-b-neutral-200`.
- **Bottom tab bar (mobile):** unchanged structurally — fixed, white, 4 items. Active item now uses `brand-700` (was `green-700`).

### Signature Section: Honest Value-Prop, Not Fabricated Social Proof
v1 shipped a testimonial marquee with invented names and quotes — a direct contradiction of PRODUCT.md's own "no fabricated realism" rule. This pivot replaces it with a "Kenapa Warlok" three-column feature section built entirely from real product mechanics (direct WhatsApp contact, QRIS visible on the shop page, wilayah-scoped results) — same visual ambition (clean icon-led feature cards) with zero invented quotes or names.

## 6. Do's and Don'ts

### Do:
- **Do** keep every actionable button fully pill-shaped.
- **Do** reserve WhatsApp green for the WhatsApp CTA only — the *only* green in the system now.
- **Do** give cards a soft shadow at rest; let hover deepen it.
- **Do** use the full hero clamp range and tight tracking — confidence over caution.
- **Do** maintain ≥44×44px touch targets and ≥4.5:1 text contrast throughout.

### Don't:
- **Don't** reintroduce green as a brand/status color anywhere outside the WhatsApp button.
- **Don't** use gradient text, tiny uppercase tracked eyebrows above every section, or 01/02/03 numbered scaffolding as a section-heading default (a *real* ordered sequence, like the "Cara Kerja" steps, may use numbers — that's information, not decoration).
- **Don't** fabricate realism — no fake reviews, no invented names/quotes, no stock photos standing in for real UMKM storefronts.
- **Don't** introduce a second typeface family.
- **Don't** go back to flat-bordered-only cards at rest — the soft shadow is load-bearing for this register.
