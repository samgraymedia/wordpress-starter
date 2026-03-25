/**
 * Vite Configuration
 *
 * Configures the Vite build system for bundling JavaScriptand processing SASS.
 * Vite provides fast development with HMR and optimized production builds.
 *
 * @see https://vitejs.dev/
 */

import { defineConfig } from "vite";

export default defineConfig({
  root: "./",
  base: "/wp-content/themes/starter-theme-1.x/",

  build: {
    // Output directory
    outDir: "dist",
    emptyOutDir: false, // Don't delete entire folder (contains other files)

    // Build options
    minify: "terser", // Minify output
    sourcemap: false, // Don't generate source maps in production
    target: "esnext",

    // Disable code splitting - keep everything in one bundle for WordPress
    rollupOptions: {
      input: {
        main: "./scripts/index.js",
      },
      output: {
        entryFileNames: "site.js",
        chunkFileNames: "[name]-[hash].js",
        assetFileNames: "[name]-[hash][extname]",
        // Prevent code splitting by merging chunks into main bundle
        manualChunks: () => undefined,
      },
    },
  },

  server: {
    // Hot Module Replacement
    hmr: false, // Disable HMR since this is enqueued via WordPress
    middlewareMode: false,
  },

  optimizeDeps: {
    // Pre-bundle these dependencies
    include: ["bootstrap"],
  },

  resolve: {
    alias: {
      "@": "/scripts",
      "@utils": "/scripts/utils",
      "@components": "/scripts/components",
      "@modules": "/scripts/modules",
    },
  },
});
