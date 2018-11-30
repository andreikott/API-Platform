<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"layout:public", "layout:read"}},
 *     denormalizationContext={"groups": {"layout:public", "layout:write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @ORM\Table(name="companies")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"layout:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Groups({"layout:public"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     * @Assert\NotNull
     * @Groups({"layout:public"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Groups({"layout:public"})
     */
    private $address;

    /**
     * @ORM\Column(type="phone_number")
     * @Assert\NotNull
     * @Groups({"layout:public"})
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Client", mappedBy="company")
     * @Groups({"layout:read"})
     * @Assert\NotNull
     */
    private $clients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="company")
     * @Assert\NotNull
     */
    private $users;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Company
     *
     * @throws \libphonenumber\NumberParseException
     */
    public function setPhone($phone): self
    {
        if (is_string($phone)) {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $phone = $phoneNumberUtil->parse($phone, PhoneNumberUtil::UNKNOWN_REGION);
        }

        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setCompany($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
            // set the owning side to null (unless already changed)
            if ($client->getCompany() === $this) {
                $client->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }
}
