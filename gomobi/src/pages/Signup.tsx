import { useState, type FormEvent } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { LogoMark } from '../components/Logo'
import Input from '../components/Input'
import Button from '../components/Button'

export default function Signup() {
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [error, setError] = useState('')
  const { signup } = useAuth()
  const navigate = useNavigate()

  const handleSubmit = (e: FormEvent) => {
    e.preventDefault()
    setError('')

    if (!name || !email || !password || !confirmPassword) {
      setError('Please fill in all fields.')
      return
    }
    if (password.length < 8) {
      setError('Password must be at least 8 characters.')
      return
    }
    if (password !== confirmPassword) {
      setError('Passwords do not match.')
      return
    }

    const result = signup(name, email, password)
    if (result.ok) {
      navigate('/dashboard')
    } else {
      setError(result.error ?? 'Unable to create account.')
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-50/60 px-4 py-10">
      <div className="w-full max-w-sm rounded-xl border border-gray-100 bg-white p-8 shadow-card">
        <div className="flex flex-col items-center">
          <LogoMark size={40} />
          <h1 className="mt-4 font-heading text-xl font-bold text-gomobi-dark">Create account</h1>
          <p className="mt-1 text-sm text-gray-500 font-body">Get started with GoMobi</p>
        </div>

        <form onSubmit={handleSubmit} className="mt-6 flex flex-col gap-4">
          <Input
            id="name"
            label="Name"
            type="text"
            placeholder="Anika Bergström"
            value={name}
            onChange={(e) => setName(e.target.value)}
          />
          <Input
            id="email"
            label="Email"
            type="email"
            placeholder="anika@example.com"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
          <Input
            id="password"
            label="Password"
            type="password"
            placeholder="At least 8 characters"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
          <Input
            id="confirmPassword"
            label="Confirm Password"
            type="password"
            placeholder="Re-enter password"
            value={confirmPassword}
            onChange={(e) => setConfirmPassword(e.target.value)}
          />
          {error && <p className="text-xs text-red-500 font-body">{error}</p>}
          <Button type="submit" fullWidth className="mt-1">
            Sign Up
          </Button>
        </form>

        <p className="mt-4 text-center text-sm text-gray-500 font-body">
          Already have an account?{' '}
          <Link to="/login" className="font-semibold text-gomobi-purple hover:underline">
            Login
          </Link>
        </p>
      </div>
    </div>
  )
}
