import {defineConfig} from "vite";
import {glob} from 'glob';
import {relative, resolve, extname} from 'node:path';
import {fileURLToPath} from 'node:url';
import * as fs from "node:fs";


const phpRefreshPlugin = () => ({
  name: "php-refresh",
  configureServer({watcher, ws}) {
    watcher.add(resolve(__dirname, "site/**/*.php"));
    watcher.on("change", function (path) {
      if (path.endsWith(".php")) {
        ws.send({
          type: "full-reload",
        });
      }
    });
  },
});

const cssNameManifest = () => ({
  name: 'css-manifest-name',
  enforce: 'post',
  writeBundle(options, bundle) {
    const manifestPath = `${options.dir}/manifest.json`;
    const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));

    for (const key in manifest) {
      if (key.endsWith('.scss')) {
        manifest[key].name = key.replace('assets/css/', 'css/').replace('.scss', '');
      }
    }
    fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2));
  }
});
export default defineConfig({
  server: {
    port: 3000,
    host: "0.0.0.0"
  },
  plugins: [phpRefreshPlugin(), cssNameManifest()],
  build: {
    outDir: 'public',
    assetsDir: '',
    manifest: "manifest.json",
    cssMinify: 'lightningcss',
    rollupOptions: {
      input: Object.fromEntries(
        glob.sync('assets/**/[^_]*.{js,scss}').map(file => [
          // This remove `src/` as well as the file extension from each
          // file, so e.g. src/nested/foo.js becomes nested/foo
          relative(
            'assets',
            file.slice(0, file.length - extname(file).length)
          ),
          // This expands the relative paths to absolute paths, so e.g.
          // src/nested/foo becomes /project/src/nested/foo.js
          fileURLToPath(new URL(file, import.meta.url))
        ])
      ),
    },
  }
});

