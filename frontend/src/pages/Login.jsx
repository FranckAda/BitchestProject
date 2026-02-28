import React, { useState } from "react";
import styled from "styled-components";

export default function Login() {
  const [register, setRegister] = useState(false);

  const [mail, setMail] = useState("");
  const [password, setPassword] = useState("");
  const [confirm, setConfirm] = useState("");

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [info, setInfo] = useState("");

  function Switch() {
    setRegister((v) => !v);
    setError("");
    setInfo("");
  }

  async function apiFetch(url, body) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);

    console.log("[API] POST", url, body);

    try {
      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "include",
        body: JSON.stringify(body),
        signal: controller.signal,
      });

      const text = await res.text();
      console.log("[API] status", res.status, "body:", text);

      let data = {};
      try {
        data = text ? JSON.parse(text) : {};
      } catch {
      }

      if (!res.ok) {
        throw new Error(data?.error || text || `Erreur HTTP ${res.status}`);
      }

      return data;
    } catch (e) {
      if (e.name === "AbortError") {
        throw new Error("Timeout: le serveur ne répond pas (10s).");
      }
      throw e;
    } finally {
      clearTimeout(timeoutId);
    }
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setError("");
    setInfo("");

    const cleanMail = mail.trim();
    if (!cleanMail || !password) {
      setError("Email et mot de passe requis.");
      return;
    }

    if (register && password !== confirm) {
      setError("Les mots de passe ne correspondent pas.");
      return;
    }

    setLoading(true);
    try {
      if (register) {
        await apiFetch("http://localhost:8000/api/register", {
          mail: cleanMail,
          password,
        });

        await apiFetch("http://localhost:8000/api/login", {
          mail: cleanMail,
          password,
        });

        setInfo("Compte créé et connecté");
      } else {
        await apiFetch("http://localhost:8000/api/login", {
          mail: cleanMail,
          password,
        });

        setInfo("Connecté");
      }

    } catch (err) {
      setError(err.message || "Erreur inconnue");
    } finally {
      setLoading(false);
    }
  }

  return (
    <Container>
      <Card>
        <Title>BitChest</Title>

        <Subtitle>{register ? "Sign up" : "Sign in"}</Subtitle>

        <form onSubmit={handleSubmit}>
          <Input
            type="email"
            placeholder="Email address"
            value={mail}
            onChange={(e) => setMail(e.target.value)}
            autoComplete="email"
          />

          <Input
            type="password"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            autoComplete={register ? "new-password" : "current-password"}
          />

          {register && (
            <Input
              type="password"
              placeholder="Confirm password"
              value={confirm}
              onChange={(e) => setConfirm(e.target.value)}
              autoComplete="new-password"
            />
          )}

          {error && <MessageError>{error}</MessageError>}
          {info && <MessageInfo>{info}</MessageInfo>}

          <PrimaryButton disabled={loading} type="submit">
            {loading ? "..." : register ? "Sign up" : "Sign in"}
          </PrimaryButton>
        </form>

        <SwitchText>
          {register ? "Already have an account?" : "Don't have an account?"}
          <SwitchSpan onClick={Switch}>
            {register ? " Sign in" : " Sign up"}
          </SwitchSpan>
        </SwitchText>
      </Card>
    </Container>
  );
}

/* ================== STYLES ================== */

const Container = styled.div`
  min-height: 100vh;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
`;

const Card = styled.div`
  background: #081b2e;
  width: 350px;
  padding: 40px 30px;
  border-radius: 12px;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
  text-align: center;
  color: white;
`;

const Title = styled.h2`
  margin-bottom: 10px;
  font-weight: bold;
`;

const Subtitle = styled.h3`
  margin-bottom: 25px;
  font-weight: 400;
`;

const Input = styled.input`
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border-radius: 8px;
  border: none;
  background: #0f2a44;
  color: white;
  outline: none;

  &::placeholder {
    color: #6fa8dc;
  }
`;

const PrimaryButton = styled.button`
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: none;
  background: linear-gradient(135deg, #00c6ff, #0072ff);
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
  opacity: ${(p) => (p.disabled ? 0.6 : 1)};

  &:hover {
    opacity: 0.9;
  }
`;

const SwitchText = styled.p`
  margin-top: 15px;
  font-size: 14px;
`;

const SwitchSpan = styled.span`
  color: #00c6ff;
  font-weight: bold;
  cursor: pointer;
  margin-left: 5px;
`;

const MessageError = styled.p`
  margin: 0 0 12px 0;
  color: #ff6b6b;
  font-size: 14px;
`;

const MessageInfo = styled.p`
  margin: 0 0 12px 0;
  color: #7bed9f;
  font-size: 14px;
`;