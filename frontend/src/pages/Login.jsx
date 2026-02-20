import React, { useState } from "react";
import styled from "styled-components";

export default function Login() {
  const [register, setRegister] = useState(false);

  function Switch() {
    setRegister(!register);
  }

  return (
    <Container>
      <Card>
        <Title>BitChest</Title>

        <Subtitle>{register ? "Sign up" : "Sign in"}</Subtitle>

        <Input type="text" placeholder="Email address" />
        <Input type="password" placeholder="Password" />

        {register && (
          <Input type="password" placeholder="Confirm password" />
        )}

        <PrimaryButton>
          {register ? "Sign up" : "Sign in"}
        </PrimaryButton>

        <SwitchText>
          {register
            ? "Already have an account?"
            : "Don't have an account?"}
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