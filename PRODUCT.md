# Product

## Register

product

## Users

**Pembeli (buyers)** — residents of a specific kelurahan/kecamatan looking for nearby warung/UMKM products or services. Job to be done: quickly find something specific or nearby, then move the conversation to WhatsApp with zero friction. Many are not power users of complex apps — they know Shopee/Tokopedia/GoFood, nothing more exotic.

**UMKM owners** — small local business owners who want to be discoverable by neighbors without running their own website or e-commerce setup. Job to be done: get listed, get found, get a WhatsApp message.

**Admin/ops team** — curates UMKM submissions (approve/reject), manages regions and categories, and keeps the catalog trustworthy while the platform is still in a single-kecamatan pilot phase.

## Product Purpose

Warlok is a hyperlocal discovery layer for UMKM (small/micro local businesses): a region-filtered catalog of nearby warungs and businesses where every "buy" action hands off to WhatsApp (chat) and QRIS (payment) rather than a built-in checkout. The platform's job is discovery and trust, not transaction processing. Success = a buyer finds a nearby UMKM fast and messages them on WhatsApp; a UMKM owner gets discovered by neighbors without needing any technical setup of their own.

## Brand Personality

**Three words (revised): Percaya Diri (Confident), Terpercaya (Trustworthy), Jernih (Clear).**

*(Superseded from the original "Hangat/Terpercaya/Membumi" — see Design Pivot note below.)* The interface has moved from mimicking Shopee/Tokopedia/GoFood's visual chrome to a cleaner, more confident modern-product register: generous white space, high-contrast type, one deliberate accent color instead of green-everywhere. The function that actually matters to adoption — WhatsApp as the transaction mechanism, wilayah-scoped results, zero account friction — is unchanged; only the visual surface pivoted. Trust now comes from clarity and craft (clean layout, honest data, real empty states) rather than from looking like the marketplace apps this audience already trusts.

### Design Pivot (recorded decision)

Warlok's public UI and admin panel were deliberately redesigned away from the original "Warm Warung" marketplace-mimicry aesthetic toward a clean, modern SaaS-product register (see [DESIGN.md](DESIGN.md) — "Clean Counter"). This was an explicit choice to prioritize visual freshness and craft over familiarity-through-mimicry. It knowingly departs from the original "familiar over novel" principle below on the *visual* register only — the underlying interaction patterns (bottom tab bar, WhatsApp handoff, wilayah picker, catalog grid) are unchanged, because those are real usability decisions, not brand chrome.

## Anti-references

- Generic AI-template landing page grammar: gradient text, tiny uppercase eyebrows on every section, numbered 01/02/03 scaffolding used as decoration (a genuine ordered sequence, like the "Cara Kerja" steps, may still use numbers), identical icon+heading+text card grids repeated with no variation.
- Fabricated realism — stock photos standing in for real UMKM storefronts, invented urgency ("only 2 left!"), fake avatar stacks or logo walls implying partnerships that don't exist. The brand's trust is built on this being real neighbors, not marketplace theater — this holds regardless of visual register.
  - **Acknowledged, deliberate exception:** the homepage "Kata Tetangga Sekitar" testimonial marquee (`x-home-testimonials`) uses illustrative names and quotes, not verified real-user submissions. This was flagged as a conflict with this exact principle and the product owner explicitly chose to keep it as-is anyway (decided 2026-07-13) rather than remove or relabel it. Don't silently "fix" this again by deleting it — if the tension matters again, raise it, don't resolve it unilaterally.
- Any interaction pattern that requires users to learn something genuinely unfamiliar for a core flow (finding a shop, messaging on WhatsApp, paying via QRIS) — novelty in *visual polish* is fine post-pivot; novelty in *core interaction* still isn't.

## Design Principles

1. **WhatsApp is the action, not a redirect.** The WA button is always the unambiguous primary CTA, using WhatsApp's own recognizable green — never buried, never disguised as a secondary action, and never diluted by using that same green anywhere else in the UI.
2. **Hyperlocal is the whole point.** Every surface should reinforce proximity and community (distance shown, wilayah picker prominent, real neighborhood names) rather than reading as a generic national marketplace.
3. **Trust through transparency, not decoration.** Real status (Buka/Tutup, pending/active), honest empty states, no invented social proof — craft and clarity build trust now, not mimicry of familiar marketplace chrome.
4. **Mobile-first, thumb-friendly.** Bottom nav, ≥44px touch targets, one-handed usable — this is browsed on a phone in the kitchen or on a motorbike, not at a desk.
5. **Confidence over caution.** Post-pivot, the visual system should read as a deliberate, well-made product — bigger type, more whitespace, one accent used decisively — not a shy, safe, generic template.

## Accessibility & Inclusion

WCAG AA minimum. High color contrast is a stated product requirement (not just a compliance checkbox) because part of the target audience is less tech-savvy and/or older. Touch targets ≥44×44px throughout. Body text never drops below comfortable reading size. Motion (marquee/particle effects) must respect `prefers-reduced-motion` with an instant or crossfade fallback.
