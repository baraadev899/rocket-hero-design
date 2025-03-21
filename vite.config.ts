
import { defineConfig } from 'vite'
import path from 'path'

export default defineConfig({
  server: {
    port: 8080
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  build: {
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'index.html'),
        notFound: path.resolve(__dirname, 'NotFound.html')
      }
    }
  }
})
