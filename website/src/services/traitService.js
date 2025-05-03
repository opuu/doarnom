import api from "./api";

export function listTraits() {
  return api.get("/traits").then((res) => res.data.payload.list);
}

export function getTrait(id) {
  return api.get(`/traits/${id}`).then((res) => res.data.payload);
}

export function createTrait(payload) {
  return api.post("/traits", payload).then((res) => res.data.payload);
}

export function updateTrait(id, payload) {
  return api.patch(`/traits/${id}`, payload).then((res) => res.data.payload);
}

export function deleteTrait(id) {
  return api.delete(`/traits/${id}`).then((res) => res.data.payload);
}
