import { User } from '@/types/User.ts';
import { Token } from '@/types/Token.ts';

export interface AuthRequest extends Token {
  user: User;
}
