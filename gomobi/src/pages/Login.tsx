import { useState, type FormEvent } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { LogoMark } from '../components/Logo'
import Input from '../components/Input'
import Button from '../components/Button'

export default function Login() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const { login } = useAuth()
  const navigate = useNavigate()

  const handleSubmit = (e: FormEvent) => {
    e.preventDefault()
    setError('')
    if (!email || !password) {
      setError('Please fill in both fields.')
      return
    }
    const result = login(email, password)
    if (result.ok) {
      navigate('/dashboard')
    } else {
      setError(result.error ?? 'Unable to login.')
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-50/60 px-4 py-10">
      <div className="w-full max-w-sm rounded-xl border border-gray-100 bg-white p-8 shadow-card">
        <div className="flex flex-col items-center">
          <LogoMark size={40} />
          <h1 className="mt-4 font-heading text-xl font-bold text-gomobi-dark">Welcome back</h1>
          <p className="mt-1 text-sm text-gray-500 font-body">Login to your GoMobi account</p>
        </div>

        <form onSubmit={handleSubmit} className="mt-6 flex flex-col gap-4">
          <Input
            id="email"
            label="Email"
            type="email"
            placeholder="admin@gomobi.com"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
          <Input
            id="password"
            label="Password"
            type="password"
            placeholder="Enter your password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
          {error && <p className="text-xs text-red-500 font-body">{error}</p>}
          <Button type="submit" fullWidth className="mt-1">
            Login
          </Button>
        </form>

        <p className="mt-4 text-center text-sm text-gray-500 font-body">
          Don't have an account?{' '}
          <Link to="/signup" className="font-semibold text-gomobi-purple hover:underline">
            Sign up
          </Link>
        </p>
      </div>
    </div>
  )
}
