# 🚀 Quick Start - Test the Theme

**Time Required**: 10 minutes  
**What You'll Do**: Activate theme, run tests, view animations

---

## Step 1: Verify Theme Installation (2 min)

```bash
cd /Applications/MAMP/htdocs/wordpress-starter-store/wp-content/themes/starter-theme-1.x

# Check all components exist
ls -la templates/components/hero.twig
ls -la templates/components/portfolio-grid.twig
ls -la templates/components/service-cards.twig
ls -la templates/home.twig

# Verify SCSS compiled
ls -lh css/style.css    # Should be ~93 KB

# Check JavaScript
ls -lh scripts/modules/animations.js    # Should be ~3 KB
```

✅ **Expected**: All files should exist and have reasonable file sizes

---

## Step 2: Run Playwright Tests (5 min)

```bash
# Install Playwright (first time only)
npm install -D @playwright/test

# Run all 30+ tests
npx playwright test

# Expected output:
# ✓ 30+ tests passed in ~30 seconds
```

**What Gets Tested**:
- ✅ Hero section displays correctly
- ✅ Portfolio items animate
- ✅ Service cards are interactive
- ✅ CTA button works
- ✅ Responsive on mobile
- ✅ Fonts load (Poppins + Inter)
- ✅ No console errors
- ✅ Page loads fast

**If You Want to See It Visually**:
```bash
# Run tests in UI mode (shows browser)
npx playwright test --ui

# Run with headed browser (watch it run)
npx playwright test --headed
```

✅ **Expected**: All tests pass (30+ ✓)

---

## Step 3: Activate Theme in WordPress (2 min)

### 3A: Go to WordPress Admin
1. Open: `http://localhost/wordpress-starter-store/wp-admin`
2. Go to **Appearance → Themes**
3. Find **Starter Theme**
4. Click **Activate**

### 3B: Create Homepage with Template
1. Go to **Pages → Add New**
2. Title: `Home` (or your preferred name)
3. In **right sidebar**, find **Template dropdown**
4. Select: **Home - Creative Studio** ✨
5. Click **Publish**

### 3C: Set as Homepage
1. Go to **Settings → Reading**
2. Select **"A static page"`
3. Set "Home page" to the page you created
4. Click **Save Changes**

✅ **Expected**: Theme is active and homepage template is selected

---

## Step 4: View the Homepage (1 min)

**In Browser**: `http://localhost/wordpress-starter-store/`

### What You Should See

#### Hero Section
- Large headline with gradient text effect: **"Create Digital Experiences That Matter"**
- Animated background with floating shapes
- Blue/gold CTA buttons with hover effects
- Three statistics at the bottom (500+, 50M+, 98%)

#### Services Section
- 6 service cards with icons (✨ 💼 💻 📱 📊 🚀)
- Each card has: Icon, Title, Description, Features, Link
- Cards lift up smoothly on hover
- Border bar reveals at top on hover

#### Portfolio Section  
- "Featured Work" heading
- Grid of 6 portfolio items
- Beautiful hover overlays with project details
- Images zoom smoothly on hover

#### CTA Section
- Dark background with floating shapes
- "Ready to Transform Your Brand?" heading
- Big call-to-action button

### Test the Interactions

| Action | Expected Result |
|--------|-----------------|
| **Hover over hero buttons** | Buttons lift up, arrow animates |
| **Hover over portfolio item** | Image zooms, overlay fades in |
| **Hover over service card** | Card lifts, icon scales, border animates |
| **Hover over CTA button** | Button lifts, arrow animates |
| **Scroll to sections** | Items fade in one by one |
| **Resize to mobile (375px)** | Layout stacks, text scales, touch-friendly |

✅ **Expected**: Smooth animations, responsive layout, no errors

---

## Step 5: Test on Different Devices (Optional, 1-2 min)

### Mobile (375px)
```bash
# In Playwright UI mode:
npx playwright test --ui

# In DevTools:
1. Press F12 (or Cmd+Option+I)
2. Click device toolbar icon
3. Select "iPhone 12"
4. Refresh page
5. Scroll through and check animations
```

### Tablet (768px)
- Open DevTools → Device toolbar → iPad
- Check grid layout (should be 2 columns)
- Verify text sizing

### Desktop (1280px+)
- Full 3-column portfolio grid
- Large hero section with side-by-side visual
- All animations smooth

---

## Step 6: Verify Design System (1 min)

### Colors Used
Open DevTools → Inspector → Right-click element:
- Hero buttons: **Teal (#00d9ff) to Gold (#ffa500) gradient**
- Background: **Deep Charcoal (#1a1f36)**
- Cards: **White with teal/gold accents**

### Fonts Used
Inspector → Computed Styles → Font-Family:
- **Headings**: Poppins (Google Fonts)
- **Body**: Inter (Google Fonts)

### Animations (Open DevTools → Performance tab)
1. Record performance
2. Scroll page from top to bottom
3. Stop recording
4. Look for smooth 60fps animations

---

## 🎉 Congratulations!

If you got here, the theme is **working perfectly**!

### Summary of What You Tested
✅ Theme installed and components built
✅ All 30+ Playwright tests passed
✅ Hero section with animations
✅ Portfolio grid with hover effects
✅ Service cards with stagger animation
✅ CTA section with conversion focus
✅ Responsive design on mobile/tablet/desktop
✅ Bold brand colors (teal, gold, charcoal)
✅ Premium fonts (Poppins + Inter)
✅ Smooth animations (spring easing)

---

## Next: Ready to Deploy?

### To Add Your Own Branding

1. **Create favicons** (5 min)
   - Design or download from: https://realfavicongenerator.net/
   - Place in: `assets/favicons/`

2. **Create social images** (10 min)
   - Design 1200x630 PNG in Figma/Canva
   - Place in: `assets/social/`

3. **Test with social debuggers** (5 min)
   - Facebook: https://developers.facebook.com/tools/debug/
   - Twitter: https://cards-dev.twitter.com/validator
   - LinkedIn: https://linkedin.com/post-inspector/

4. **Deploy to production** (varies)
   - Upload theme
   - Add assets
   - Test live site

---

## 📚 More Information

- **Full Build Details**: Read [CREATIVE-STUDIO-BUILD.md](CREATIVE-STUDIO-BUILD.md)
- **Component Documentation**: Check templates in `templates/components/`
- **SCSS Architecture**: See `css/inc/components/`
- **Test Code**: Review `tests/e2e/theme.spec.ts`

---

## ❓ Troubleshooting

### "Tests fail"
```bash
# Make sure dependencies are installed
npm install

# Make sure Playwright is installed
npm install -D @playwright/test

# Run tests with debug
npx playwright test --debug
```

### "Theme doesn't show up in WordPress"
1. Verify theme is in: `wp-content/themes/starter-theme-1.x/`
2. Check `style.css` has proper header (first 15 lines)
3. Go to Appearance → Themes → Scroll to find it
4. Sometimes need to clear cache: Delete transients in database

### "Animations are choppy"
- Could be browser cache - hard refresh (Cmd+Shift+R or Ctrl+Shift+R)
- Check DevTools Performance tab for JavaScript errors
- Verify animations.js loaded: Open DevTools → Sources → site.js

### "Homepage template doesn't appear"
1. Check: `src/page-templates/home.php` exists
2. Verify theme is activated (not just installed)
3. Go to Pages → Edit page → Check right sidebar for template dropdown
4. Clear WordPress cache if using caching plugin

---

## 🎬 Quick Demo Checklist

Before showing someone else, verify:
- [ ] Theme is activated
- [ ] Home page is set as homepage
- [ ] Page loads without errors (check DevTools console)
- [ ] Hero section animates on load
- [ ] Service cards hover smoothly
- [ ] Portfolio items zoom on hover
- [ ] CTA button works
- [ ] Mobile view looks good (375px)
- [ ] Font is bold and distinctive (not generic)
- [ ] Colors are vibrant (teal + gold + charcoal)

---

**You're all set! The theme is ready to impress. 🚀**
