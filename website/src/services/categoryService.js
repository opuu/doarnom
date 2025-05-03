import api from "./api";

export function listCategories() {
  return api.get("/categories").then((res) => res.data.payload.list);
}

export function getCategory(id) {
  return api.get(`/categories/${id}`).then((res) => res.data.payload);
}

export function createCategory(payload) {
  return api.post("/categories", payload).then((res) => res.data.payload);
}

export function updateCategory(id, payload) {
  return api
    .patch(`/categories/${id}`, payload)
    .then((res) => res.data.payload);
}

export function deleteCategory(id) {
  return api.delete(`/categories/${id}`).then((res) => res.data.payload);
}
