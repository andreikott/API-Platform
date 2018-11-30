<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"layout:public", "layout:read"}},
 *     denormalizationContext={"groups": {"layout:public", "layout:write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 * @ORM\Table(name="requests")
 */
class Request
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
     * @Groups({"layout:public"})
     */
    public $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"layout:public"})
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Groups({"layout:public"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"layout:public"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"layout:public"})
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"layout:public"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="request")
     * @Groups({"layout:public"})
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"layout:public"})
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="requests")
     */
    private $users;

    public function __construct()
    {
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

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
            $user->addRequest($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeRequest($this);
        }

        return $this;
    }
}
