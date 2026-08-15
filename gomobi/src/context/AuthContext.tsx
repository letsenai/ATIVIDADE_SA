import { createContext, useContext, useState, useEffect, type ReactNode } from 'react'
import type { User } from '../types'

interface StoredAccount {
  name: string
  email: string
  password: string
}

interface AuthContextValue {
  user: User | null
  isAuthenticated: boolean
  login: (email: string, password: string) => { ok: boolean; error?: string }
  signup: (name: string, email: string, password: string) => { ok: boolean; error?: string }
  logout: () => void
  updatePassword: (
    current: string,
    next: string
  ) => { ok: boolean; error?: string }
}

const AuthContext = createContext<AuthContextValue | undefined>(undefined)

const ACCOUNTS_KEY = 'gomobi_accounts'
const SESSION_KEY = 'gomobi_session'

function readAccounts(): StoredAccount[] {
  try {
    const raw = localStorage.getItem(ACCOUNTS_KEY)
    return raw ? (JSON.parse(raw) as StoredAccount[]) : []
  } catch {
    return []
  }
}

function writeAccounts(accounts: StoredAccount[]) {
  localStorage.setItem(ACCOUNTS_KEY, JSON.stringify(accounts))
}

function buildUser(account: StoredAccount): User {
  const now = new Date().toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  })
  return {
    name: account.name || 'User',
    email: account.email,
    role: 'viewer',
    status: 'active',
    memberSince: now,
    lastLogin: now,
  }
}

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null)
  const [loaded, setLoaded] = useState(false)

  useEffect(() => {
    try {
      const raw = localStorage.getItem(SESSION_KEY)
      if (raw) {
        setUser(JSON.parse(raw) as User)
      }
    } catch {
      // ignore corrupted session
    } finally {
      setLoaded(true)
    }
  }, [])

  const login: AuthContextValue['login'] = (email, password) => {
    const accounts = readAccounts()
    let account = accounts.find(
      (a) => a.email.toLowerCase() === email.toLowerCase()
    )

    // Seed a default demo account so the reference login screen works out of the box.
    if (!account && email.toLowerCase() === 'admin@gomobi.com') {
      account = { name: 'Admin', email, password: password || 'password' }
      accounts.push(account)
      writeAccounts(accounts)
    }

    if (!account) {
      return { ok: false, error: 'No account found with that email.' }
    }
    if (account.password !== password) {
      return { ok: false, error: 'Incorrect password.' }
    }
    const builtUser = buildUser(account)
    setUser(builtUser)
    localStorage.setItem(SESSION_KEY, JSON.stringify(builtUser))
    return { ok: true }
  }

  const signup: AuthContextValue['signup'] = (name, email, password) => {
    const accounts = readAccounts()
    if (accounts.some((a) => a.email.toLowerCase() === email.toLowerCase())) {
      return { ok: false, error: 'An account with that email already exists.' }
    }
    const account: StoredAccount = { name, email, password }
    accounts.push(account)
    writeAccounts(accounts)
    const builtUser = buildUser(account)
    setUser(builtUser)
    localStorage.setItem(SESSION_KEY, JSON.stringify(builtUser))
    return { ok: true }
  }

  const logout = () => {
    setUser(null)
    localStorage.removeItem(SESSION_KEY)
  }

  const updatePassword: AuthContextValue['updatePassword'] = (current, next) => {
    if (!user) return { ok: false, error: 'Not authenticated.' }
    const accounts = readAccounts()
    const idx = accounts.findIndex(
      (a) => a.email.toLowerCase() === user.email.toLowerCase()
    )
    if (idx === -1) return { ok: false, error: 'Account not found.' }
    if (accounts[idx].password !== current) {
      return { ok: false, error: 'Current password is incorrect.' }
    }
    accounts[idx].password = next
    writeAccounts(accounts)
    return { ok: true }
  }

  if (!loaded) return null

  return (
    <AuthContext.Provider
      value={{ user, isAuthenticated: !!user, login, signup, logout, updatePassword }}
    >
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const ctx = useContext(AuthContext)
  if (!ctx) throw new Error('useAuth must be used within AuthProvider')
  return ctx
}
