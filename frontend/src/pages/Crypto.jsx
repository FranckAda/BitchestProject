import { useEffect, useState } from "react";
import BuyCrypto from "../Components/Buy";

export default function CryptoDashbord({ userId }) {
  const [cryptos, setCrypto] = useState([]);
  const [acquieredCryptos, setAcquieredCrypto] = useState([]);
  const [user, setUser] = useState({});

  useEffect(() => {
    fetch("/api/crypto")
      .then((r) => r.json())
      .then((data) => setCrypto(data.CryptoCurrencies || []))
      .catch((err) => console.error("health error:", err));
    fetch("api/acquieredcrypto")
      .then((r) => r.json())
      .then((data) => setAcquieredCrypto(data.acquieredCrypto || []))
      .catch((err) => console.error("health error:", err));
    fetch(`/api/admin/${userId}`)
      .then((r) => r.json())
      .then((data) => setUser(data || []))
      .catch((err) => console.error("user error:", err));
  }, [userId]);

  const groupedCryptos = Object.values(
    acquieredCryptos.reduce((acc, crypto) => {
      const key = `${crypto.crypto.name}-${crypto.value}`;

      if (!acc[key]) {
        acc[key] = {
          name: crypto.crypto.name,
          value: crypto.value,
          quantity: 0,
        };
      }

      acc[key].quantity += 1;

      return acc;
    }, {}),
  );

  return (
    <>
      {cryptos
        ? cryptos.map((crypto) => {
            return (
              <div key={crypto.id}>
                <ul>
                  <li>{crypto.id}</li>
                  <li>{crypto.actualValue}</li>
                </ul>
                <BuyCrypto cryptoId={crypto.id} walletInfo={user.wallet} />
              </div>
            );
          })
        : ""}
      <h3>You have acquiered :</h3>
      {groupedCryptos.map((crypto, index) => (
        <div key={index}>
          <ul>
            <li>{crypto.name}</li>
            <li>Unit value: {crypto.value}</li>
            <li>Quantity: {crypto.quantity}</li>
            <li>Total: {crypto.value * crypto.quantity}</li>
          </ul>
        </div>
      ))}
    </>
  );
}
