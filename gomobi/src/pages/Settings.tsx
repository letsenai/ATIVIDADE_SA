import { useState, type FormEvent } from 'react'
import { useNavigate } from 'react-router-dom'
import { User as UserIcon, Lock, LogOut } from 'lucide-react'
import { useAuth } from '../context/AuthContext'
import Card from '../components/Card'
import Input from '../components/Input'
import Button from '../components/Button'
import StatusBadge from '../components/StatusBadge'

export default function Settings() {
  const { user, updatePassword, logout } = useAuth()
  const navigate = useNavigate()

  const [currentPassword, setCurrentPassword] = useState('')
  const [newPassword, setNewPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [message, setMessage] = useState<{ type: 'error' | 'success'; text: string } | null>(null)

  const handleUpdatePassword = (e: FormEvent) => {
    e.preventDefault()
    setMessage(null)

    if (!currentPassword || !newPassword || !confirmPassword) {
      setMessage({ type: 'error', text: 'Please fill in all fields.' })
      return
    }
    if (newPassword.length < 8) {
      setMessage({ type: 'error', text: 'New password must be at least 8 characters.' })
      return
    }
    if (newPassword !== confirmPassword) {
      setMessage({ type: 'error', text: 'Passwords do not match.' })
      return
    }

    const result = updatePassword(currentPassword, newPassword)
    if (result.ok) {
      setMessage({ type: 'success', text: 'Password updated successfully.' })
      setCurrentPassword('')
      setNewPassword('')
      setConfirmPassword('')
    } else {
      setMessage({ type: 'error', text: result.error ?? 'Unable to update password.' })
    }
  }

  const handleLogout = () => {
    logout()
    navigate('/login')
  }

  return (
    <div>
      <h1 className="font-heading text-2xl font-bold text-gomobi-dark">Settings</h1>
      <p className="mt-1 text-sm text-gray-500 font-body">
        Manage your account and preferences
      </p>

      <div className="mt-6 flex max-w-xl flex-col gap-5">
        <Card>
          <div className="mb-4 flex items-center gap-2">
            <UserIcon size={15} className="text-gomobi-dark" />
            <p className="text-sm font-semibold text-gomobi-dark font-body">Profile Information</p>
          </div>
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <p className="text-xs text-gray-500 font-body">Name</p>
              <p className="mt-1 text-sm text-gomobi-dark font-body">{user?.name}</p>
            </div>
            <div>
              <p className="text-xs text-gray-500 font-body">Email</p>
              <p className="mt-1 text-sm text-gomobi-dark font-body">{user?.email}</p>
            </div>
            <div>
              <p className="text-xs text-gray-500 font-body">Role</p>
              <div className="mt-1">
                <StatusBadge status={user?.role ?? 'viewer'} showDot={false} />
              </div>
            </div>
            <div>
              <p className="text-xs text-gray-500 font-body">Status</p>
              <div className="mt-1">
                <StatusBadge status={user?.status ?? 'active'} showDot={false} />
              </div>
            </div>
            <div>
              <p className="text-xs text-gray-500 font-body">Member Since</p>
              <p className="mt-1 text-sm text-gomobi-dark font-body">{user?.memberSince ?? '-'}</p>
            </div>
            <div>
              <p className="text-xs text-gray-500 font-body">Last Login</p>
              <p className="mt-1 text-sm text-gomobi-dark font-body">{user?.lastLogin ?? '-'}</p>
            </div>
          </div>
        </Card>

        <Card>
          <div className="mb-4 flex items-center gap-2">
            <Lock size={15} className="text-gomobi-dark" />
            <p className="text-sm font-semibold text-gomobi-dark font-body">Change Password</p>
          </div>
          <form onSubmit={handleUpdatePassword} className="flex flex-col gap-4">
            <Input
              label="Current Password"
              type="password"
              value={currentPassword}
              onChange={(e) => setCurrentPassword(e.target.value)}
            />
            <Input
              label="New Password"
              type="password"
              value={newPassword}
              onChange={(e) => setNewPassword(e.target.value)}
            />
            <Input
              label="Confirm New Password"
              type="password"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
            />
            {message && (
              <p className={`text-xs font-body ${message.type === 'error' ? 'text-red-500' : 'text-emerald-600'}`}>
                {message.text}
              </p>
            )}
            <Button type="submit" className="self-start">
              Update Password
            </Button>
          </form>
        </Card>

        <Card>
          <div className="mb-4 flex items-center gap-2">
            <LogOut size={15} className="text-gomobi-dark" />
            <p className="text-sm font-semibold text-gomobi-dark font-body">Account Actions</p>
          </div>
          <Button variant="danger" onClick={handleLogout}>
            Logout
          </Button>
        </Card>
      </div>
    </div>
  )
}
