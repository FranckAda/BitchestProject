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

export default function App() {
  const [count, setCount] = useState(0);

  useEffect(() => {
    fetch("/api/health") // ✅ passe par le proxy Vite
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
      </Routes>
    </Router>
  );
}
