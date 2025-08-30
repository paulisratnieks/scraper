FROM node:lts-alpine AS build-stage

WORKDIR /app

COPY /web/package*.json ./

RUN npm install

COPY /web .

RUN npm run build

FROM nginx:1.27.2 AS production-stage

COPY --from=build-stage /app/dist /usr/share/nginx/html

COPY /docker/prod/web/default.conf /etc/nginx/conf.d/default.conf

CMD ["nginx", "-g", "daemon off;"]
