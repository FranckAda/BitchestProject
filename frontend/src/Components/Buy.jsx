import { useState } from "react";

export default function BuyCrypto({ cryptoId, userBalance }) {
  const [crypto, setCrypto] = useState();

  const handleSubmit = async (e) => {
    e.preventDefault();
    fetch(`/api/crypto/${cryptoId}`)
      .then((r) => r.json())
      .then((data) => {
        setCrypto(data || "");
      });
    if (crypto && crypto.actualValue > userBalance) {
      alert("You don't have enough balance to buy this crypto.");
      return;
    } else {
      const res = await fetch(`/api/acquieredcrypto/new`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(crypto),
      });

      const data = await res.json().catch(() => null);
      console.log("PATCH result:", data);
    }
  };
  console.log(crypto);

  return <button onClick={handleSubmit}> Buy Crypto {cryptoId} </button>;
}
