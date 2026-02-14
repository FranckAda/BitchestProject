import { useEffect, useState } from "react";
import BuyCrypto from "../Components/Buy";

export default function CryptoDashbord({ userId }) {
  const [cryptos, setCrypto] = useState([]);
  const [user, setUser] = useState({});
  useEffect(() => {
    fetch("/api/crypto")
      .then((r) => r.json())
      .then((data) => setCrypto(data || []))
      .catch((err) => console.error("health error:", err));
    fetch(`/api/admin/${userId}`)
      .then((r) => r.json())
      .then((data) => setUser(data || []))
      .catch((err) => console.error("user error:", err));
  }, [userId]);

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
                <BuyCrypto
                  cryptoId={crypto.id}
                  userBalance={user.wallet.balance}
                />
              </div>
            );
          })
        : ""}
    </>
  );
}
