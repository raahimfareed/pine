import { defineConfig } from "vite";
export default defineConfig({
    publicDir: "src/resources/public",
    build: {
        outDir: "public",
        write: true,
        lib: {
            entry: ['src/resources/js/index.js', 'src/resources/css/index.css'],
            name: 'pine',
            fileName: 'main',
        },
    }
});
