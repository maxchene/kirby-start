import {defineConfig} from "vite";
import {glob} from 'glob';
import {relative, resolve, extname} from 'node:path';
import {fileURLToPath} from 'node:url';
import * as path from "node:path";
import {readdirSync, statSync, readFileSync, writeFileSync, mkdirSync} from 'fs';


const phpRefreshPlugin = () => ({
  name: "php-refresh",
  configureServer({watcher, ws}) {
    watcher.add(resolve(__dirname, "site/**/*.php"));
    watcher.on("change", function (path) {
      if (path.endsWith(".php")) {
        ws.send({
          type: "full-reload"
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
    const manifest = JSON.parse(readFileSync(manifestPath, 'utf8'));
    const newManifest = {};
    for (const key in manifest) {
      let newKey = key;
      if (key.endsWith('.scss')) {
        newKey = key.replace('.scss', '.css');
      } else if (key.endsWith('.css')) {
        newKey = key;
      }

      newManifest[newKey] = manifest[key];
    }
    writeFileSync(manifestPath, JSON.stringify(newManifest, null, 2));
  }
});

const inputFiles = glob.sync('assets/js/*.js').reduce((entries, file) => {
  const name = path.parse(file).name
  entries[name] = path.resolve(__dirname, file)
  return entries
}, {})

const IconSpritePlugin = () => {
  function generateIconSprite() {
    // Read the SVG files in the static/icons folder
    const iconsDir = path.join(process.cwd(), 'assets', 'icons');
    const files = readdirSync(iconsDir);
    let symbols = '';

    // Build up the SVG sprite from the SVG files
    for (const file of files) {
      if (!file.endsWith('.svg')) continue;
      let svgContent = readFileSync(path.join(iconsDir, file), 'utf8');
      const id = file.replace('.svg', '');
      svgContent = svgContent
        .replace(/(fill|width|height)="[^"]+"/g, '')
        .replace(/\s{2,}/g, ' ')
        .replace(/\s+>/g, '>') // Supprime les espaces avant `>`
        .replace(/id="[^"]+"/, '') // Remove any existing id
        .replace('<svg', `<symbol id="${id}"`) // Change <svg> to <symbol>
        .replace('</svg>', '</symbol>');
      symbols += svgContent + '\n';
    }

    // Write the SVG sprite to a file in the static folder
    const sprite = `<svg xmlns="http://www.w3.org/2000/svg">\n${symbols}</svg>`;
    mkdirSync(path.join(process.cwd(), 'public'), {recursive: true});
    writeFileSync(path.join(process.cwd(), 'public', 'sprite.svg'), sprite);
  }

  return {
    name: 'icon-sprite-plugin',
    closeBundle() {
      generateIconSprite();
    },
    configureServer(server) {
      // Regenerate during development whenever an icon is added
      server.watcher.add(path.join(process.cwd(), 'assets', 'icons', '*.svg'));
      server.watcher.on('change', async (changedPath) => {
        if (changedPath.endsWith('.svg')) return generateIconSprite();
      });
    },
  };
}
export default defineConfig(({command}) => ({
  base: command === 'build' ? '/public' : '/',
  server: {
    port: 3000,
    host: "0.0.0.0"
  },
  cors: true,
  plugins: [phpRefreshPlugin(), IconSpritePlugin(), cssNameManifest()],
  build: {
    outDir: 'public/',
    assetsDir: '',
    manifest: "manifest.json",
    cssMinify: 'lightningcss',
    rollupOptions: {
      input: {
        ...getCssEntries(resolve(__dirname, 'assets/css')),
        ...inputFiles
      }
    },
  }
}));

function getCssEntries(cssDir) {
  const entries = {};
  const files = readdirSync(cssDir);
  for (const file of files) {
    const fullPath = resolve(cssDir, file);
    const stats = statSync(fullPath);
    if (stats.isFile() && (file.endsWith('.css') || file.endsWith('.scss'))) {
      const name = `css/${file.replace(/\.scss$/, '.css')}`;
      entries[name] = fullPath;
    }
  }
  return entries;
}
