import {defineConfig} from "vite";
import {glob} from 'glob';
import path from 'node:path';
import {fileURLToPath} from 'node:url';
import.meta.path;

export default defineConfig({
  server: {
    port: 3000,
    host: "0.0.0.0"
  },
  plugins: [HotReloadPhpFiles],
  build: {
    outDir: '',
    assetsDir: 'public',
    manifest: "manifest.json",
    cssMinify: 'lightningcss',
    rollupOptions: {
      input: Object.fromEntries(
        glob.sync('assets/**/[^_]*.{js,scss}').map(file => [
          // This remove `src/` as well as the file extension from each
          // file, so e.g. src/nested/foo.js becomes nested/foo
          path.relative(
            'assets',
            file.slice(0, file.length - path.extname(file).length)
          ),
          // This expands the relative paths to absolute paths, so e.g.
          // src/nested/foo becomes /project/src/nested/foo.js
          fileURLToPath(new URL(file, import.meta.url))
        ])
      ),
    },
  }
});

export function HotReloadPhpFiles() {

  return {
    name: 'custom-hmr',
    enforce: 'post',
    // HMR
    handleHotUpdate({file, server}) {
      // only hot reload files with php extension inside 'site' folder
      if (file.endsWith('.php')) {
        server.ws.send({
          type: 'full-reload',
          path: '*'
        });
      }
    },
  }
}
