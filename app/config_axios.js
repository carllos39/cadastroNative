import axios from "axios";

export const inAxios = axios.create({
  baseURL: "http://192.168.1.2:80/cadastro/api/",
  timeout: 10000,
});