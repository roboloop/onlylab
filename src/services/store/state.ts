import localforage from 'localforage'

export interface ForumState {
  filter: string
  hideIgnored: boolean
}

export interface TopicState {
  filter: string
}

export const store: LocalForage = localforage.createInstance({
  name: 'state',
  driver: [localforage.LOCALSTORAGE],
})

const forumKey = 'forum'
const defaultForumState: ForumState = {
  filter: '',
  hideIgnored: true,
}

const topicKey = 'topic'
const defaultTopicState: TopicState = {
  filter: '',
}

export async function getForumState(): Promise<ForumState> {
  return { ...defaultForumState, ...(await store.getItem<ForumState>(forumKey)) }
}

export async function getTopicState(): Promise<TopicState> {
  return { ...defaultTopicState, ...(await store.getItem<TopicState>(topicKey)) }
}

export async function putForumState(forumState: ForumState): Promise<void> {
  await store.setItem<ForumState>(forumKey, forumState)
}

export async function putTopicState(topicState: TopicState): Promise<void> {
  await store.setItem<TopicState>(topicKey, topicState)
}

export async function clearStore(): Promise<void> {
  await store.clear()
}
