import { useEffect, useState } from "react";
import {
  BrowserRouter as Router,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";
import Login from "./pages/Login";
import "./App.css";
import EditForm from "./Components/EditForm";
import NewForm from "./Components/NewForm";

export default function App() {
  
  useEffect(() => {
    fetch("/api/health") 
      .then((r) => r.json())
      .then((data) => console.log("health:", data))
      .catch((err) => console.error("health error:", err));
  }, []);

  return (
    <Router>
      <Routes>
        <Route path="/" element={<Navigate to="/login" replace />} />
        <Route path="/login" element={<Login />} />
        <Route path="/edit" element={<EditForm userId={1} />} />
        <Route path="/new" element={<NewForm />} />
      </Routes>
    </Router>
  );
}
