import { useEffect, useState } from "react";
import DeleteUser from "./Delete";

export default function Dashbord() {
  const [users, setUsers] = useState([]);

  useEffect(() => {
    fetch("/api/admin")
      .then((r) => r.json())
      .then((data) => setUsers(data.users || []))
      .catch((err) => console.error("health error:", err));
  }, []);

  return (
    <>
      {users.map((user) => {
        return (
          <div key={user.id}>
            <ul>
              <li>{user.id}</li>
              <li>{user.mail}</li>
              <li>{user.role}</li>
            </ul>
            <DeleteUser userId={user.id} />
          </div>
        );
      })}
    </>
  );
}
