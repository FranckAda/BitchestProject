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
  console.log(acquieredCryptos);

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
      {acquieredCryptos
        ? acquieredCryptos.map((crypto) => {
            return (
              <div key={crypto.id}>
                <ul>
                  <li>{crypto.crypto.name}</li>
                  <li>{crypto.value}</li>
                </ul>
              </div>
            );
          })
        : ""}
    </>
  );
}
